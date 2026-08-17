<?php

// Lokasi folder

namespace App\Filament\Resources\ProfilSekolahs\Schemas;

// Elemen form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * ProfilSekolahForm
 *
 * Mengatur susunan isian saat menulis Sejarah / Visi / Misi sekolah.
 */
class ProfilSekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Pilihan drop-down: Ini konten tentang apa?
                Select::make('jenis')
                    ->label('Jenis Konten')
                    ->options([
                        'visi' => 'Visi',
                        'misi' => 'Misi',
                        'sejarah' => 'Sejarah',
                        'sambutan' => 'Kata Sambutan',
                    ])
                    ->required(), // Wajib diisi

                // Kotak isian untuk judul (contoh: "Sejarah Berdirinya SD Passo")
                TextInput::make('judul')
                    ->label('Judul Konten')
                    ->required(),

                // Kotak teks besar untuk mengetik isi ceritanya
                Textarea::make('isi')
                    ->label('Isi Tulisan')
                    ->required()
                    ->rows(8) // Diperbesar menjadi 8 baris agar lebih lega untuk mengetik
                    ->columnSpanFull(), // Penuhi layar (panjang dari ujung kiri ke kanan)
            ]);
    }
}
