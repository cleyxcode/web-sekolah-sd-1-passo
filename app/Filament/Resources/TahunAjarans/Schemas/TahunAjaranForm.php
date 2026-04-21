<?php

namespace App\Filament\Resources\TahunAjarans\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TahunAjaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama')
                    ->required(),
                Select::make('semester')
                    ->options([1 => '1', '2'])
                    ->required(),
                Toggle::make('is_active')
                    ->required(),
                DatePicker::make('tanggal_mulai'),
                DatePicker::make('tanggal_selesai'),
            ]);
    }
}
