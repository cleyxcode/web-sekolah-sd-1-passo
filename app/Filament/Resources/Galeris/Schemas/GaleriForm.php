<?php

namespace App\Filament\Resources\Galeris\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
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
                FileUpload::make('file_path')
                    ->label('File Foto/Video')
                    ->directory('galeri')
                    ->acceptedFileTypes(['image/*', 'video/*'])
                    ->maxSize(10240)
                    ->required(),
                Select::make('jenis')
                    ->options(['foto' => 'Foto', 'video' => 'Video'])
                    ->default('foto')
                    ->required(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                \Filament\Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }
}
