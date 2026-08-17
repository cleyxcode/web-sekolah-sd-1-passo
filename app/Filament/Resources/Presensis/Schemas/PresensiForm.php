<?php

// Lokasi folder

namespace App\Filament\Resources\Presensis\Schemas;

// Model database
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
// Elemen Form
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

/**
 * PresensiForm
 *
 * Mengatur susunan kotak isian saat guru sedang mendata kehadiran (absen) siswa.
 */
class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Bagian 1: Memilih Siswa mana yang mau diabsen
                Section::make('Identitas Siswa')
                    ->description('Pilih kelas terlebih dahulu, lalu pilih siswa.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Grid::make(2)->schema([

                            // Pilihan Drop-down Kelas
                            Select::make('kelas_id')
                                ->label('Filter Kelas (Opsional)')
                                // Mengambil data kelas dari database
                                ->options(
                                    Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                                        ->get()
                                        ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"])
                                )
                                ->searchable()
                                ->live() // Jika pilihan ini diubah, form akan langsung bereaksi (loading sesaat)
                                // Jika kelas diubah, maka kotak isian "Siswa" akan di-reset (dikosongkan)
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', null))
                                // Sembunyikan kotak pencarian kelas ini JIKA yang login adalah seorang Guru (Guru otomatis hanya milih kelasnya sendiri)
                                ->hidden(fn () => Auth::user()?->hasRole('Guru')),

                            // Pilihan Drop-down Siswa
                            Select::make('siswa_id')
                                ->label('Siswa')
                                ->required() // Wajib diisi
                                ->searchable() // Bisa dicari namanya
                                ->live() // Bereaksi saat diubah
                                // Jika siswa dipilih, maka kotak "Kelas" otomatis terisi sesuai dengan kelas siswa tersebut
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state) {
                                        $siswa = Siswa::find($state);
                                        if ($siswa) {
                                            $set('kelas_id', $siswa->kelas_id);
                                        }
                                    }
                                })
                                // Menentukan daftar siswa yang muncul di drop-down
                                ->options(function ($get) {
                                    $user = Auth::user();
                                    // Ambil semua siswa yang masih aktif
                                    $query = Siswa::with('kelas')->where('status', 'aktif')->orderBy('nama');

                                    // JIKA YANG LOGIN ADALAH GURU:
                                    if ($user?->hasRole('Guru')) {
                                        $guru = Guru::where('user_id', $user->id)->first();
                                        if ($guru) {
                                            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                                            if ($kelasIds->isEmpty()) {
                                                return [];
                                            }
                                            // Hanya tampilkan siswa di kelas perwaliannya
                                            $query->whereIn('kelas_id', $kelasIds);
                                        } else {
                                            return [];
                                        }
                                    }
                                    // JIKA YANG LOGIN ADMIN:
                                    else {
                                        // Filter siswa berdasarkan kelas yang dipilih admin di kolom 'kelas_id' sebelumnya
                                        $kelasId = $get('kelas_id');
                                        if ($kelasId) {
                                            $query->where('kelas_id', $kelasId);
                                        }
                                    }

                                    // Merapikan nama agar ada info kelasnya (contoh: Budi (Kelas 1A))
                                    return $query->get()->mapWithKeys(function ($s) {
                                        $label = $s->nama;
                                        if ($s->kelas) {
                                            $label .= " (Kelas {$s->kelas->nama_kelas})";
                                        }

                                        return [$s->id => $label];
                                    });
                                })
                                ->helperText(fn () => Auth::user()?->hasRole('Guru')
                                    ? 'Daftar siswa di kelas yang Anda wali.'
                                    : 'Pilih kelas terlebih dahulu untuk memfilter (opsional).')
                                // Memastikan saat halaman Edit dibuka, form 'kelas' langsung terisi otomatis dari data 'siswa'
                                ->afterStateHydrated(function ($state, $set) {
                                    if ($state) {
                                        $siswa = Siswa::find($state);
                                        if ($siswa) {
                                            $set('kelas_id', $siswa->kelas_id);
                                        }
                                    }
                                }),
                        ]),
                    ]),

                // Bagian 2: Data Status Kehadiran (Hadir, Sakit, dll)
                Section::make('Data Kehadiran')
                    ->description('Isi data kehadiran dan keterangan jika perlu.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        Grid::make(3)->schema([

                            // Kotak Tanggal
                            DatePicker::make('tanggal')
                                ->label('Tanggal')
                                ->required()
                                ->default(now()) // Bawaan hari ini
                                ->maxDate(now()), // Tidak boleh absen untuk tanggal besok

                            // Drop-down Guru yang ngabsen
                            Select::make('guru_id')
                                ->label('Guru Pencatat')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                // Otomatis terisi oleh guru yang login
                                ->default(function () {
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }

                                    return null;
                                }),

                            // Drop-down Tahun Ajaran
                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->options(fn () => TahunAjaran::orderByDesc('nama')->pluck('nama', 'id'))
                                ->required()
                                // Otomatis terpilih tahun ajaran terbaru
                                ->default(fn () => TahunAjaran::orderByDesc('nama')->value('id')),
                        ]),

                        // Pilihan Status (Hadir, Sakit, Izin, Alpha)
                        Select::make('status')
                            ->label('Status Kehadiran')
                            ->options([
                                'hadir' => '✅ Hadir',
                                'sakit' => '🤒 Sakit',
                                'izin' => '📝 Izin',
                                'alpha' => '❌ Alpha (Tanpa Keterangan)',
                            ])
                            ->required()
                            ->native(false) // Desain popup cantik
                            ->default('hadir'),

                        // Keterangan teks
                        Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->helperText('Isi keterangan jika sakit, izin, atau alpha.')
                            ->columnSpanFull(),
                    ]),

                // Bagian 3: Bukti Foto Surat Sakit dll
                Section::make('Bukti Foto Absen')
                    ->description('Upload foto daftar hadir (opsional). Foto ini akan tampil di portal orang tua.')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        FileUpload::make('foto_absen')
                            ->label('Foto Absen')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120) // Maksimal 5MB
                            ->directory('absen-foto') // Simpan di folder absen-foto
                            ->visibility('public')
                            ->helperText('Format: JPG, PNG, WebP. Maks 5MB.')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(), // Ditutup secara bawaan agar rapi
            ]);
    }
}
