<?php

namespace App\Filament\Resources\SettingSekolahs\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class SettingSekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('nama_sekolah'),
                TextInput::make('logo'),
                Textarea::make('alamat')
                    ->columnSpanFull(),
                TextInput::make('no_telepon')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                TextInput::make('instagram'),
                TextInput::make('facebook'),
                TextInput::make('youtube'),
                TextInput::make('website')
                    ->url(),
                TextInput::make('kepala_sekolah'),
                TextInput::make('npsn'),
                TextInput::make('akreditasi'),
                TextInput::make('link_ppdb'),
            ]);
    }
}
