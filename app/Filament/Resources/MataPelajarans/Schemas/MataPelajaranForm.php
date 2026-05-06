<?php

// Lokasi folder
namespace App\Filament\Resources\MataPelajarans\Schemas;

// Form
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * MataPelajaranForm
 * 
 * Mengatur susunan kotak isian untuk membuat Mata Pelajaran baru.
 */
class MataPelajaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // Kotak nama mapel
                TextInput::make('nama')
                    ->label('Nama Mata Pelajaran')
                    ->placeholder('Contoh: Ilmu Pengetahuan Alam')
                    ->required(),
                
                // Kotak kode mapel (singkatan)
                TextInput::make('kode')
                    ->label('Kode Mata Pelajaran')
                    ->placeholder('Contoh: IPA')
                    ->required(),
                
                // Kotak penentuan tingkat kelas
                TextInput::make('tingkat_kelas')
                    ->label('Tingkat Kelas (Opsional)')
                    ->numeric() // Hanya terima angka
                    ->helperText('Kosongkan jika pelajaran ini berlaku untuk semua kelas (misal: Agama). Isi angka 1-6 jika khusus untuk kelas tertentu (misal: Tematik Kelas 1).'),
            ]);
    }
}
