<?php

// Lokasi folder

namespace App\Filament\Resources\JadwalPelajarans\Schemas;

// Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

/**
 * JadwalPelajaranForm
 *
 * Mengatur kotak isian untuk membuat jadwal pelajaran baru.
 */
class JadwalPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Drop-down Kelas
                Select::make('kelas_id')
                    ->label('Pilih Kelas')
                    ->relationship('kelas', 'nama_kelas')
                    ->required()
                    ->searchable()
                    ->preload(),

                // Drop-down Pelajaran
                Select::make('mata_pelajaran_id')
                    ->label('Pilih Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),

                // Drop-down Guru Pengajar
                Select::make('guru_id')
                    ->label('Pilih Guru Pengajar')
                    ->relationship('guru', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),

                // Nama Hari
                TextInput::make('hari')
                    ->label('Hari')
                    ->placeholder('Contoh: Senin, Selasa, Rabu')
                    ->required(),

                // Pilihan Jam (Model Jam Digital)
                TimePicker::make('jam_mulai')
                    ->label('Jam Mulai (00:00)')
                    ->required(),
                TimePicker::make('jam_selesai')
                    ->label('Jam Selesai (00:00)')
                    ->required(),
            ]);
    }
}
