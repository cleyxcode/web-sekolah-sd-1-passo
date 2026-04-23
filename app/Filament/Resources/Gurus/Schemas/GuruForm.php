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
                TextInput::make('nip')
                    ->label('NIP (Opsional)'),
                TextInput::make('nama')
                    ->label('Nama Lengkap')
                    ->required(),
                TextInput::make('email')
                    ->label('Email Akun')
                    ->email()
                    ->required()
                    ->unique(table: 'users', column: 'email', ignorable: fn ($record) => $record?->user),
                TextInput::make('password')
                    ->label('Password Akun')
                    ->password()
                    ->revealable()
                    ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                    ->minLength(6)
                    ->dehydrated(fn ($state) => filled($state))
                    ->helperText('Kosongkan saat edit jika tidak ingin mengubah password.'),
                Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                    ->required(),
                TextInput::make('no_telepon')
                    ->label('No. Telepon')
                    ->tel(),
            ]);
    }
}
