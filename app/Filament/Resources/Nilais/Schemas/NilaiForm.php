<?php

// Lokasi folder

namespace App\Filament\Resources\Nilais\Schemas;

// Model
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
// Komponen Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * NilaiForm
 *
 * Mengatur susunan kotak isian untuk memasukkan nilai ujian siswa.
 * Form ini cukup cerdas karena bisa mendeteksi otomatis guru yang sedang login
 * dan hanya memunculkan mata pelajaran sesuai tingkat kelas siswanya.
 */
class NilaiForm
{
    public static function configure(Schema $schema): Schema
    {
        // 1. CEK SIAPA YANG SEDANG LOGIN
        $user = Auth::user();
        $isSuperAdmin = $user?->hasRole('Super Admin');
        $guru = Guru::where('user_id', $user?->id)->first();

        // Cari tahu guru ini wali dari kelas apa?
        $kelasWali = $guru
            ? Kelas::where('wali_kelas_id', $guru->id)->first()
            : null;

        // Tandai dengan True jika guru ini benar wali kelas
        $isWaliKelas = $kelasWali !== null;

        // 2. DAPATKAN DAFTAR KELAS
        // Panggil fungsi getKelasOptions di bawah untuk menentukan kelas apa saja yg bisa dipilih di drop-down
        $kelasOptions = self::getKelasOptions($isSuperAdmin, $guru, $kelasWali);

        return $schema
            ->components([

                // --- BAGIAN 1: IDENTITAS SISWA ---
                Section::make('Identitas Siswa')
                    ->description('Pilih kelas dan siswa untuk pengisian nilai.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Grid::make(2)->schema([

                            // Pilihan Kelas
                            Select::make('kelas_id')
                                ->label('Kelas')
                                ->options($kelasOptions)
                                ->searchable()
                                ->required()
                                ->live() // Bereaksi jika pilihan kelas berubah

                                // Jika yang login Wali Kelas, pilihan otomatis terkunci di kelasnya sendiri
                                ->default(function () use ($isSuperAdmin, $kelasWali) {
                                    if (! $isSuperAdmin && $kelasWali) {
                                        return $kelasWali->id;
                                    }

                                    return null;
                                })
                                // Reset nama siswa jika kelas diganti
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', null))
                                // Nonaktifkan (bekukan kotak) jika ia adalah wali kelas
                                ->disabled(fn () => ! $isSuperAdmin && $isWaliKelas)
                                ->dehydrated() // Tetap simpan nilainya ke database walaupun kotaknya dibekukan (disabled)
                                // Tampilkan pesan bantuan agar guru tidak bingung
                                ->helperText(function () use ($isSuperAdmin, $isWaliKelas, $kelasWali) {
                                    if ($isSuperAdmin) {
                                        return null;
                                    }
                                    if ($isWaliKelas) {
                                        return 'Otomatis terisi karena Anda adalah Wali Kelas '.$kelasWali->nama_kelas.'.';
                                    }

                                    return '⚠️ Anda belum terdaftar sebagai Wali Kelas. Hubungi Super Admin.';
                                }),

                            // Pilihan Siswa
                            Select::make('siswa_id')
                                ->label('Siswa')
                                ->required()
                                ->searchable()
                                ->preload()
                                // Daftar siswa disaring sesuai dengan kelas yang dipilih di kotak sebelumnya
                                ->options(function ($get) {
                                    $kelasId = $get('kelas_id');
                                    if (! $kelasId) {
                                        return [];
                                    } // Kosong kalau kelas belum dipilih

                                    // Ambil siswa aktif di kelas tersebut
                                    return Siswa::where('kelas_id', $kelasId)
                                        ->where('status', 'aktif')
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id');
                                })
                                // Kotak ini dibekukan (disabled) JIKA kotak kelas masih kosong
                                ->disabled(fn ($get) => ! $get('kelas_id'))
                                ->helperText('Daftar siswa aktif di kelas yang dipilih.'),
                        ]),
                    ]),

                // --- BAGIAN 2: DETAIL NILAI ---
                Section::make('Detail Nilai')
                    ->description('Isi mata pelajaran, semester, jenis ujian, dan nilai.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([

                            // Pilihan Mata Pelajaran
                            Select::make('mata_pelajaran_id')
                                ->label('Mata Pelajaran')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options(function ($get) use ($kelasWali) {
                                    $kelasId = $get('kelas_id');

                                    // Cek kelas mana yang dipilih saat ini
                                    $kelas = $kelasId
                                        ? Kelas::find($kelasId)
                                        : ($kelasWali ?? null);

                                    // Kalau tidak ada kelas, tampilkan semua mapel saja
                                    if (! $kelas) {
                                        return MataPelajaran::orderBy('nama')->pluck('nama', 'id');
                                    }

                                    // FITUR PINTAR: Saring mapel HANYA yang sesuai dengan tingkat kelasnya
                                    // Contoh: Mapel "Tematik Kelas 1" tidak akan muncul kalau dia sedang mengisi nilai untuk anak Kelas 6
                                    return MataPelajaran::where(function ($q) use ($kelas) {
                                        $q->where('tingkat_kelas', $kelas->tingkat)
                                            ->orWhereNull('tingkat_kelas'); // Termasuk mapel umum yang tak punya tingkat
                                    })
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id');
                                })
                                ->helperText('Mata pelajaran sesuai tingkat kelas yang dipilih.'),

                            // Pilihan Guru Penginput (Wali Kelas)
                            Select::make('guru_id')
                                ->label('Wali Kelas / Penginput')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                // Otomatis terisi nama guru yang login
                                ->default(fn () => $guru?->id)
                                // Dikunci (disabled) agar tidak bisa diganti, kecuali dia Super Admin
                                ->disabled(fn () => ! $isSuperAdmin)
                                ->dehydrated()
                                ->helperText(fn () => ! $isSuperAdmin
                                    ? 'Otomatis terisi dengan akun Wali Kelas Anda.'
                                    : null
                                ),
                        ]),

                        Grid::make(3)->schema([

                            // Pilihan Tahun Ajaran
                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->options(fn () => TahunAjaran::orderByDesc('nama')->pluck('nama', 'id'))
                                ->required()
                                ->searchable()
                                ->default(fn () => TahunAjaran::orderByDesc('nama')->value('id')),

                            // Pilihan Semester
                            Select::make('semester')
                                ->label('Semester')
                                ->options(['1' => 'Semester 1', '2' => 'Semester 2'])
                                ->required()
                                ->native(false),

                            // Pilihan Jenis Ujian
                            Select::make('jenis_ujian')
                                ->label('Jenis Ujian')
                                ->options([
                                    'UTS' => 'UTS (Ujian Tengah Semester)',
                                    'UAS' => 'UAS (Ujian Akhir Semester)',
                                ])
                                ->required()
                                ->native(false),
                        ]),

                        // Kotak Memasukkan Angka Nilai (0 - 100)
                        TextInput::make('nilai_angka')
                            ->label('Nilai Angka')
                            ->required()
                            ->numeric() // Hanya terima angka
                            ->minValue(0)   // Minimal 0
                            ->maxValue(100) // Maksimal 100
                            ->step(0.5)     // Bisa pakai desimal koma lima (misal: 80.5)
                            ->suffix('/ 100') // Muncul tulisan "/100" redup di ujung kotak
                            ->helperText('Masukkan nilai antara 0 - 100.'),
                    ]),
            ]);
    }

    /**
     * FUNGSI BANTUAN: Menentukan kelas apa saja yang muncul di drop-down
     * - Super Admin: Muncul SEMUA kelas
     * - Wali Kelas: Hanya muncul SATU kelas miliknya saja
     * - Guru lain (non-wali): Kosong (Tidak muncul apa-apa)
     */
    private static function getKelasOptions(bool $isSuperAdmin, ?Guru $guru, ?Kelas $kelasWali): array|Collection
    {
        if ($isSuperAdmin) {
            return Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                ->get()
                ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"]);
        }

        if ($kelasWali) {
            return collect([$kelasWali->id => "Kelas {$kelasWali->nama_kelas} (Wali Kelas Anda)"]);
        }

        return collect();
    }
}
