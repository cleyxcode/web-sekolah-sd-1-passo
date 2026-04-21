<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                TextInput::make('file_path')
                    ->required(),
                Select::make('jenis')
                    ->options(['foto' => 'Foto', 'video' => 'Video'])
                    ->default('foto')
                    ->required(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
            ]);
    }
}
