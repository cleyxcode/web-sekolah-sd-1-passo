<?php

namespace App\Filament\Resources\Pendaftarans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class PendaftaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->label('Judul Pendaftaran')
                    ->required(),
                Textarea::make('deskripsi')
                    ->label('Deskripsi Singkat')
                    ->columnSpanFull(),
                TextInput::make('link_pendaftaran')
                    ->label('URL / Link Pendaftaran')
                    ->url()
                    ->required(),
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)
                    ->required(),
            ]);
    }
}
