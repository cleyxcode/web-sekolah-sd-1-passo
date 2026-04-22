<?php

namespace App\Filament\Resources\CatatanPerkembangans\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CatatanPerkembanganForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\Select::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                \Filament\Forms\Components\Select::make('predikat')
                    ->options([
                        'Sangat Baik' => 'Sangat Baik (Cerdas)',
                        'Baik' => 'Baik',
                        'Berkembang' => 'Mulai Berkembang',
                        'Perlu Bimbingan' => 'Perlu Bimbingan Tambahan'
                    ])
                    ->required(),
                Textarea::make('catatan')
                    ->required()
                    ->columnSpanFull(),
            ]);
    }
}
