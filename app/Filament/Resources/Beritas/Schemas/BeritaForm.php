<?php

namespace App\Filament\Resources\Beritas\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
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
                Textarea::make('isi')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('foto'),
                TextInput::make('kategori'),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'publish' => 'Publish'])
                    ->default('draft')
                    ->required(),
                \Filament\Forms\Components\Select::make('user_id')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->label('Pembuat (Akun)'),
                DateTimePicker::make('published_at'),
            ]);
    }
}
