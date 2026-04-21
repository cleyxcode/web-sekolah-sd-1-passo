<?php

namespace App\Filament\Resources\JadwalPelajarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class JadwalPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('kelas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('mata_pelajaran_id')
                    ->required()
                    ->numeric(),
                TextInput::make('guru_id')
                    ->required()
                    ->numeric(),
                TextInput::make('hari')
                    ->required(),
                TimePicker::make('jam_mulai')
                    ->required(),
                TimePicker::make('jam_selesai')
                    ->required(),
            ]);
    }
}
