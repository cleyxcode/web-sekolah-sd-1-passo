<?php

namespace App\Filament\Resources\Presensis\Schemas;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class PresensiForm
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
                                ->hidden(fn () => Auth::user()?->hasRole('Guru')),

                            Select::make('siswa_id')
                                ->label('Siswa')
                                ->required()
                                ->searchable()
                                ->live()
                                ->afterStateUpdated(function ($state, $set) {
                                    if ($state) {
                                        $siswa = \App\Models\Siswa::find($state);
                                        if ($siswa) $set('kelas_id', $siswa->kelas_id);
                                    }
                                })
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

                Section::make('Data Kehadiran')
                    ->description('Isi data kehadiran dan keterangan jika perlu.')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->schema([
                        Grid::make(3)->schema([
                            DatePicker::make('tanggal')
                                ->label('Tanggal')
                                ->required()
                                ->default(now())
                                ->maxDate(now()),

                            Select::make('guru_id')
                                ->label('Guru Pencatat')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->default(function () {
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }
                                    return null;
                                }),

                            Select::make('tahun_ajaran_id')
                                ->label('Tahun Ajaran')
                                ->options(fn () => TahunAjaran::orderByDesc('nama')->pluck('nama', 'id'))
                                ->required()
                                ->default(fn () => TahunAjaran::orderByDesc('nama')->value('id')),
                        ]),

                        Select::make('status')
                            ->label('Status Kehadiran')
                            ->options([
                                'hadir' => '✅ Hadir',
                                'sakit' => '🤒 Sakit',
                                'izin'  => '📝 Izin',
                                'alpha' => '❌ Alpha (Tanpa Keterangan)',
                            ])
                            ->required()
                            ->native(false)
                            ->default('hadir'),

                        Textarea::make('keterangan')
                            ->label('Keterangan Tambahan')
                            ->helperText('Isi keterangan jika sakit, izin, atau alpha.')
                            ->columnSpanFull(),
                    ]),

                Section::make('Bukti Foto Absen')
                    ->description('Upload foto daftar hadir (opsional). Foto ini akan tampil di portal orang tua.')
                    ->icon('heroicon-o-camera')
                    ->schema([
                        FileUpload::make('foto_absen')
                            ->label('Foto Absen')
                            ->image()
                            ->imagePreviewHeight('200')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                            ->maxSize(5120) // 5MB
                            ->directory('absen-foto')
                            ->visibility('public')
                            ->helperText('Format: JPG, PNG, WebP. Maks 5MB.')
                            ->columnSpanFull(),
                    ])
                    ->collapsed(),
            ]);
    }
}
