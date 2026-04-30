<?php

namespace App\Filament\Resources\Nilais\Schemas;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class NilaiForm
{
    public static function configure(Schema $schema): Schema
    {
        $user         = Auth::user();
        $isSuperAdmin = $user?->hasRole('Super Admin');
        $guru         = Guru::where('user_id', $user?->id)->first();

        // Wali kelas: guru yang menjadi wali kelas di suatu kelas
        $kelasWali = $guru
            ? Kelas::where('wali_kelas_id', $guru->id)->first()
            : null;

        $isWaliKelas = $kelasWali !== null;

        // Opsi kelas yang tersedia
        $kelasOptions = self::getKelasOptions($isSuperAdmin, $guru, $kelasWali);

        return $schema
            ->components([
                Section::make('Identitas Siswa')
                    ->description('Pilih kelas dan siswa untuk pengisian nilai.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Grid::make(2)->schema([

                            Select::make('kelas_id')
                                ->label('Kelas')
                                ->options($kelasOptions)
                                ->searchable()
                                ->required()
                                ->live()
                                // Wali kelas: otomatis terpilih dan dikunci
                                ->default(function () use ($isSuperAdmin, $kelasWali) {
                                    if (!$isSuperAdmin && $kelasWali) {
                                        return $kelasWali->id;
                                    }
                                    return null;
                                })
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', null))
                                ->disabled(fn () => !$isSuperAdmin && $isWaliKelas)
                                ->dehydrated()
                                ->helperText(function () use ($isSuperAdmin, $isWaliKelas, $kelasWali) {
                                    if ($isSuperAdmin) return null;
                                    if ($isWaliKelas) return 'Otomatis terisi karena Anda adalah Wali Kelas ' . $kelasWali->nama_kelas . '.';
                                    return '⚠️ Anda belum terdaftar sebagai Wali Kelas. Hubungi Super Admin.';
                                }),

                            Select::make('siswa_id')
                                ->label('Siswa')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options(function ($get) {
                                    $kelasId = $get('kelas_id');
                                    if (!$kelasId) return [];
                                    return Siswa::where('kelas_id', $kelasId)
                                        ->where('status', 'aktif')
                                        ->orderBy('nama')
                                        ->pluck('nama', 'id');
                                })
                                ->disabled(fn ($get) => !$get('kelas_id'))
                                ->helperText('Daftar siswa aktif di kelas yang dipilih.'),
                        ]),
                    ]),

                Section::make('Detail Nilai')
                    ->description('Isi mata pelajaran, semester, jenis ujian, dan nilai.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([

                            Select::make('mata_pelajaran_id')
                                ->label('Mata Pelajaran')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->options(function ($get) use ($isSuperAdmin, $kelasWali) {
                                    $kelasId = $get('kelas_id');

                                    // Ambil objek kelas: dari state form atau default wali kelas
                                    $kelas = $kelasId
                                        ? Kelas::find($kelasId)
                                        : ($kelasWali ?? null);

                                    if (!$kelas) {
                                        return MataPelajaran::orderBy('nama')->pluck('nama', 'id');
                                    }

                                    // Filter mata pelajaran berdasarkan tingkat kelas
                                    return MataPelajaran::where(function ($q) use ($kelas) {
                                        $q->where('tingkat_kelas', $kelas->tingkat)
                                          ->orWhereNull('tingkat_kelas');
                                    })
                                    ->orderBy('nama')
                                    ->pluck('nama', 'id');
                                })
                                ->helperText('Mata pelajaran sesuai tingkat kelas yang dipilih.'),

                            // Field Guru Pengajar — otomatis dari wali kelas, hanya Super Admin bisa ubah
                            Select::make('guru_id')
                                ->label('Wali Kelas / Penginput')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->default(fn () => $guru?->id)
                                ->disabled(fn () => !$isSuperAdmin)
                                ->dehydrated()
                                ->helperText(fn () => !$isSuperAdmin
                                    ? 'Otomatis terisi dengan akun Wali Kelas Anda.'
                                    : null
                                ),
                        ]),

                        Grid::make(3)->schema([

                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->options(fn () => TahunAjaran::orderByDesc('nama')->pluck('nama', 'id'))
                                ->required()
                                ->searchable()
                                ->default(fn () => TahunAjaran::orderByDesc('nama')->value('id')),

                            Select::make('semester')
                                ->label('Semester')
                                ->options(['1' => 'Semester 1', '2' => 'Semester 2'])
                                ->required()
                                ->native(false),

                            Select::make('jenis_ujian')
                                ->label('Jenis Ujian')
                                ->options([
                                    'UTS' => 'UTS (Ujian Tengah Semester)',
                                    'UAS' => 'UAS (Ujian Akhir Semester)',
                                ])
                                ->required()
                                ->native(false),
                        ]),

                        TextInput::make('nilai_angka')
                            ->label('Nilai Angka')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->maxValue(100)
                            ->step(0.5)
                            ->suffix('/ 100')
                            ->helperText('Masukkan nilai antara 0 - 100.'),
                    ]),
            ]);
    }

    /**
     * Ambil opsi kelas:
     * - Super Admin: semua kelas
     * - Wali Kelas: hanya kelas yang ia ampu
     * - Guru biasa (bukan wali kelas): tidak ada opsi
     */
    private static function getKelasOptions(bool $isSuperAdmin, ?Guru $guru, ?Kelas $kelasWali): array|\Illuminate\Support\Collection
    {
        if ($isSuperAdmin) {
            return Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                ->get()
                ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"]);
        }

        if ($kelasWali) {
            // Hanya kelas yang ia walikan
            return collect([$kelasWali->id => "Kelas {$kelasWali->nama_kelas} (Wali Kelas Anda)"]);
        }

        return collect();
    }
}
