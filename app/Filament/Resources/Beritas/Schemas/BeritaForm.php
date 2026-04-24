<?php

namespace App\Filament\Resources\Beritas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class BeritaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('judul')
                    ->required(),
                TextInput::make('slug')
                    ->required(),
                RichEditor::make('isi')
                    ->required()
                    ->columnSpanFull(),
                FileUpload::make('foto')
                    ->image()
                    ->directory('berita')
                    ->maxSize(5120),
                TextInput::make('kategori'),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'publish' => 'Publish'])
                    ->default('draft')
                    ->required(),
                \Filament\Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
                DateTimePicker::make('published_at'),
            ]);
    }
}
