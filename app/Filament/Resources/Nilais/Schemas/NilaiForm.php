<?php

namespace App\Filament\Resources\Nilais\Schemas;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class NilaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Identitas Siswa')
                    ->description('Pilih siswa berdasarkan kelas terlebih dahulu.')
                    ->icon('heroicon-o-user-group')
                    ->schema([
                        Grid::make(2)->schema([
                            // Filter kelas dulu, lalu siswa otomatis terfilter
                            Select::make('kelas_id')
                                ->label('Kelas')
                                ->options(
                                    Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                                        ->get()
                                        ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas} (Tingkat {$k->tingkat})"])
                                )
                                ->searchable()
                                ->required()
                                ->live() // reactive: mempengaruhi dropdown siswa
                                ->afterStateUpdated(fn ($set) => $set('siswa_id', null)),

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
                                ->helperText('Pilih kelas terlebih dahulu.'),
                        ]),
                    ]),

                Section::make('Detail Nilai')
                    ->description('Isi data mata pelajaran, ujian, dan nilai angka.')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('mata_pelajaran_id')
                                ->label('Mata Pelajaran')
                                ->relationship('mataPelajaran', 'nama')
                                ->required()
                                ->searchable()
                                ->preload(),

                            Select::make('guru_id')
                                ->label('Guru Pengajar')
                                ->relationship('guru', 'nama')
                                ->required()
                                ->searchable()
                                ->preload()
                                ->default(function () {
                                    // Otomatis pilih guru dari user yang login
                                    $user = Auth::user();
                                    if ($user?->hasRole('Guru')) {
                                        return Guru::where('user_id', $user->id)->value('id');
                                    }
                                    return null;
                                }),
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
}
