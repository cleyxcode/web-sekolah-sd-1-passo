<?php

namespace App\Filament\Resources\Nilais\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NilaiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('mata_pelajaran_id')
                    ->relationship('mataPelajaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('semester')
                    ->options([1 => '1', '2'])
                    ->required(),
                Select::make('jenis_ujian')
                    ->options(['UTS' => 'U t s', 'UAS' => 'U a s'])
                    ->required(),
                TextInput::make('nilai_angka')
                    ->required()
                    ->numeric(),
            ]);
    }
}
