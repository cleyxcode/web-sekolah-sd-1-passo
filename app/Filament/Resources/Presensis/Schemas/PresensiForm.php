<?php

namespace App\Filament\Resources\Presensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('siswa_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kelas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('guru_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('tanggal')
                    ->required(),
                Select::make('status')
                    ->options(['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alpha' => 'Alpha'])
                    ->required(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                TextInput::make('tahun_ajaran_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
