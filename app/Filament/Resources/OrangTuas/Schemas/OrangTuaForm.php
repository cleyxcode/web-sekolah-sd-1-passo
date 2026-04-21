<?php

namespace App\Filament\Resources\OrangTuas\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OrangTuaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('nama')
                    ->required(),
                TextInput::make('no_telepon')
                    ->tel(),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                TextInput::make('pekerjaan'),
            ]);
    }
}
