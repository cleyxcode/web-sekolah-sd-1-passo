<?php

namespace App\Filament\Resources\Presensis\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class PresensiForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('siswa_id')
                    ->relationship('siswa', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()
                    ->searchable()
                    ->preload(),
                Select::make('guru_id')
                    ->relationship('guru', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
                DatePicker::make('tanggal')
                    ->required(),
                Select::make('status')
                    ->options(['hadir' => 'Hadir', 'sakit' => 'Sakit', 'izin' => 'Izin', 'alpha' => 'Alpha'])
                    ->required(),
                Textarea::make('keterangan')
                    ->columnSpanFull(),
                Select::make('tahun_ajaran_id')
                    ->relationship('tahunAjaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
