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
                TextInput::make('siswa_id')
                    ->required()
                    ->numeric(),
                TextInput::make('mata_pelajaran_id')
                    ->required()
                    ->numeric(),
                TextInput::make('guru_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kelas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tahun_ajaran_id')
                    ->required()
                    ->numeric(),
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
