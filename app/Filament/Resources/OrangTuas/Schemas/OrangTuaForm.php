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
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->label('Akun Pengguna (Opsional)'),
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
