<?php

// Lokasi folder

namespace App\Filament\Resources\CatatanPerkembangans\Schemas;

// Model
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
// Komponen Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

/**
 * CatatanPerkembanganForm
 *
 * Mengatur susunan kotak isian saat guru menuliskan pesan perilaku / perkembangan siswa.
 */
class CatatanPerkembanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // --- BAGIAN 1: IDENTITAS SISWA ---
                Section::make('Identitas Siswa')
                    ->description('Pilih kelas terlebih dahulu, lalu pilih siswa.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Grid::make(2)->schema([

                            // Drop-down Filter Kelas
                            Select::make('kelas_id')
                                ->label('Filter Kelas (Opsional)')
                                ->options(
                                    Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                                        ->get()
                                        ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"])
                                )
                                ->searchable()
                                ->live() // Bereaksi jika diubah
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', null)) // Kosongkan nama siswa jika kelas diganti
                                ->dehydrated(false) // Data kelas_id ini TIDAK disimpan ke tabel CatatanPerkembangan (karena tidak ada kolomnya)
                                ->hidden(fn () => Auth::user()?->hasRole('Guru')), // Sembunyikan kotak pencarian kelas JIKA yang login Guru (Otomatis kelas dia)

                            // Drop-down Pilihan Siswa
                            Select::make('siswa_id')
                                ->label('Siswa')
                                ->required()
                                ->searchable()
                                // Saring daftar nama siswa
                                ->options(function ($get) {
                                    $user = Auth::user();
                                    // Ambil siswa yang statusnya aktif saja
                                    $query = Siswa::with('kelas')->where('status', 'aktif')->orderBy('nama');

                                    // JIKA YANG LOGIN GURU
                                    if ($user?->hasRole('Guru')) {
                                        $guru = Guru::where('user_id', $user->id)->first();
                                        if ($guru) {
                                            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                                            if ($kelasIds->isEmpty()) {
                                                return [];
                                            }
                                            // Cuma tampilkan siswa di kelasnya guru tersebut
                                            $query->whereIn('kelas_id', $kelasIds);
                                        } else {
                                            return [];
                                        }
                                    }
                                    // JIKA YANG LOGIN ADMIN / KEPSEK
                                    else {
                                        $kelasId = $get('kelas_id');
                                        // Filter siswa berdasarkan kelas yang diplih di kotak sebelumnya (jika ada)
                                        if ($kelasId) {
                                            $query->where('kelas_id', $kelasId);
                                        }
                                    }

                                    // Tuliskan nama siswanya beserta kelasnya agar gampang dicari
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

                // --- BAGIAN 2: ISI CATATAN ---
                Section::make('Catatan Perkembangan')
                    ->description('Isi evaluasi karakter dan perkembangan siswa.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([

                            // Siapa guru yang mencatat ini
                            Select::make('guru_id')
                                ->label('Guru Pengajar / Wali Kelas')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                // Otomatis isikan nama guru yang sedang login
                                ->default(function () {
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }

                                    return null;
                                })
                                ->helperText('Otomatis terisi jika login sebagai Guru.'),

                            // Pilihan predikat bintang
                            Select::make('predikat')
                                ->label('Predikat Perkembangan')
                                ->options([
                                    'Sangat Baik' => '⭐ Sangat Baik',
                                    'Baik' => '👍 Baik',
                                    'Berkembang' => '📈 Mulai Berkembang',
                                    'Perlu Bimbingan' => '🔔 Perlu Bimbingan Tambahan',
                                ])
                                ->required()
                                ->native(false),
                        ]),

                        // Kotak besar untuk mengetik pesannya
                        Textarea::make('catatan')
                            ->label('Catatan Perkembangan')
                            ->required()
                            ->rows(5)
                            ->placeholder('Tuliskan catatan perkembangan karakter, sikap, atau akademik siswa secara singkat dan jelas...')
                            ->helperText('Catatan ini akan ditampilkan di portal orang tua.')
                            ->columnSpanFull(), // Lebarkan kotak teks dari kiri sampai kanan
                    ]),
            ]);
    }
}
