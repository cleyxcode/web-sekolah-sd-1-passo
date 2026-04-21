<?php

namespace App\Filament\Resources\Siswas\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SiswaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nis')
                    ->required(),
                TextInput::make('nama')
                    ->required(),
                Select::make('jenis_kelamin')
                    ->options(['L' => 'L', 'P' => 'P'])
                    ->required(),
                DatePicker::make('tanggal_lahir'),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                TextInput::make('foto'),
                TextInput::make('kelas_id')
                    ->numeric(),
                TextInput::make('tahun_ajaran_id')
                    ->numeric(),
                Select::make('status')
                    ->options(['aktif' => 'Aktif', 'lulus' => 'Lulus', 'pindah' => 'Pindah'])
                    ->default('aktif')
                    ->required(),
            ]);
    }
}
