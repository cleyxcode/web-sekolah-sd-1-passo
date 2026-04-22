<?php

namespace App\Filament\Resources\Kelas\Schemas;

use App\Models\Guru;
use App\Models\TahunAjaran;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Filament\Schemas\Schema;

class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('nama_kelas')
                ->label('Nama Kelas')
                ->placeholder('Contoh: 1A, 2B, 3A')
                ->required()
                ->maxLength(20)
                ->columnSpan(1),

            Select::make('tingkat')
                ->label('Tingkat / Kelas')
                ->options([
                    1 => 'Kelas 1',
                    2 => 'Kelas 2',
                    3 => 'Kelas 3',
                    4 => 'Kelas 4',
                    5 => 'Kelas 5',
                    6 => 'Kelas 6',
                ])
                ->required()
                ->columnSpan(1),

            Select::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'nama')
                ->required()
                ->searchable()
                ->preload()
                ->default(fn () => TahunAjaran::where('is_active', true)->first()?->id)
                ->columnSpan(1),

            Select::make('wali_kelas_id')
                ->label('Wali Kelas')
                ->relationship('waliKelas', 'nama')
                ->searchable()
                ->preload()
                ->placeholder('Pilih guru wali kelas...')
                ->columnSpan(1),
        ])->columns(2);
    }
}
