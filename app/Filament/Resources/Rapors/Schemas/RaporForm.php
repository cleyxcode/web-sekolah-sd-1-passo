<?php

namespace App\Filament\Resources\Rapors\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class RaporForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('siswa_id')
                    ->required()
                    ->numeric(),
                TextInput::make('kelas_id')
                    ->required()
                    ->numeric(),
                TextInput::make('tahun_ajaran_id')
                    ->required()
                    ->numeric(),
                Select::make('semester')
                    ->options([1 => '1', '2'])
                    ->required(),
                TextInput::make('total_hadir')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_sakit')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_izin')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_alpha')
                    ->required()
                    ->numeric()
                    ->default(0),
                Textarea::make('catatan_wali_kelas')
                    ->columnSpanFull(),
                Select::make('status')
                    ->options(['draft' => 'Draft', 'final' => 'Final'])
                    ->default('draft')
                    ->required(),
                DateTimePicker::make('generated_at'),
                TextInput::make('file_path'),
            ]);
    }
}
