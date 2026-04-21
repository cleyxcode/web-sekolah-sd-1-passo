<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()
                    ->required(),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $context): bool => $context === 'create'),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
                \Filament\Forms\Components\Select::make('roles')
                    ->label('Peran (Role)')
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->preload(),
            ]);
    }
}
