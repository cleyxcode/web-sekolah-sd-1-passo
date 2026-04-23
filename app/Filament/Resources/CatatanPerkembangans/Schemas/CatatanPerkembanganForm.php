<?php

namespace App\Filament\Resources\CatatanPerkembangans\Schemas;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class CatatanPerkembanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identitas Siswa')
                    ->description('Pilih kelas terlebih dahulu, lalu pilih siswa.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('kelas_id')
                                ->label('Filter Kelas (Opsional)')
                                ->options(
                                    Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                                        ->get()
                                        ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"])
                                )
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', null))
                                ->dehydrated(false)
                                ->hidden(fn () => Auth::user()?->hasRole('Guru')),

                            Select::make('siswa_id')
                                ->label('Siswa')
                                ->required()
                                ->searchable()
                                ->options(function ($get) {
                                    $user = Auth::user();
                                    $query = Siswa::with('kelas')->where('status', 'aktif')->orderBy('nama');

                                    if ($user?->hasRole('Guru')) {
                                        $guru = Guru::where('user_id', $user->id)->first();
                                        if ($guru) {
                                            $kelasIds = Kelas::where('wali_kelas_id', $guru->id)->pluck('id');
                                            if ($kelasIds->isEmpty()) return [];
                                            $query->whereIn('kelas_id', $kelasIds);
                                        } else {
                                            return [];
                                        }
                                    } else {
                                        $kelasId = $get('kelas_id');
                                        if ($kelasId) {
                                            $query->where('kelas_id', $kelasId);
                                        }
                                    }

                                    return $query->get()->mapWithKeys(function ($s) {
                                        $label = $s->nama;
                                        if ($s->kelas) $label .= " (Kelas {$s->kelas->nama_kelas})";
                                        return [$s->id => $label];
                                    });
                                })
                                ->helperText(fn() => Auth::user()?->hasRole('Guru') 
                                    ? 'Daftar siswa di kelas yang Anda wali.' 
                                    : 'Pilih kelas terlebih dahulu untuk memfilter (opsional).')
                                ->afterStateHydrated(function ($state, $set) {
                                    if ($state) {
                                        $siswa = Siswa::find($state);
                                        if ($siswa) $set('kelas_id', $siswa->kelas_id);
                                    }
                                }),
                        ]),
                    ]),

                Section::make('Catatan Perkembangan')
                    ->description('Isi evaluasi karakter dan perkembangan siswa.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('guru_id')
                                ->label('Guru Pengajar / Wali Kelas')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->default(function () {
                                    // Auto-fill guru dari user yang sedang login
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }
                                    return null;
                                })
                                ->helperText('Otomatis terisi jika login sebagai Guru.'),

                            Select::make('predikat')
                                ->label('Predikat Perkembangan')
                                ->options([
                                    'Sangat Baik'      => '⭐ Sangat Baik',
                                    'Baik'             => '👍 Baik',
                                    'Berkembang'       => '📈 Mulai Berkembang',
                                    'Perlu Bimbingan'  => '🔔 Perlu Bimbingan Tambahan',
                                ])
                                ->required()
                                ->native(false),
                        ]),

                        Textarea::make('catatan')
                            ->label('Catatan Perkembangan')
                            ->required()
                            ->rows(5)
                            ->placeholder('Tuliskan catatan perkembangan karakter, sikap, atau akademik siswa secara singkat dan jelas...')
                            ->helperText('Catatan ini akan ditampilkan di portal orang tua.')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
