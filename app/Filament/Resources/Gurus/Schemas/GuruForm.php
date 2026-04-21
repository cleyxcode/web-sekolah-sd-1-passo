<?php

namespace App\Filament\Resources\Gurus\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('nip'),
                TextInput::make('nama')
                    ->required(),
                Select::make('jenis_kelamin')
                    ->options(['L' => 'L', 'P' => 'P'])
                    ->required(),
                DatePicker::make('tanggal_lahir'),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                TextInput::make('foto'),
                TextInput::make('no_telepon')
                    ->tel(),
            ]);
    }
}
