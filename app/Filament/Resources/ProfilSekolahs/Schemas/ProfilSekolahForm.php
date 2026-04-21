<?php

namespace App\Filament\Resources\ProfilSekolahs\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ProfilSekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('jenis')
                    ->options(['visi' => 'Visi', 'misi' => 'Misi', 'sejarah' => 'Sejarah', 'sambutan' => 'Sambutan'])
                    ->required(),
                TextInput::make('judul')
                    ->required(),
                Textarea::make('isi')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
