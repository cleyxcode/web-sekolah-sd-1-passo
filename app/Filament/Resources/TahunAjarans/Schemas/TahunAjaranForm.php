<?php

// Alamat folder tempat file ini berada
namespace App\Filament\Resources\TahunAjarans\Schemas;

// Mengimpor elemen-elemen untuk menyusun formulir
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * TahunAjaranForm
 * 
 * Kelas ini mengatur bentuk kotak-kotak isian (Form) 
 * saat admin membuat atau mengedit data Tahun Ajaran.
 */
class TahunAjaranForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            // ->components() digunakan untuk meletakkan komponen/kotak isian
            ->components([
                
                // Kotak teks biasa untuk nama tahun ajaran
                TextInput::make('nama')
                    ->label('Nama Tahun Ajaran') // Judul di atas kotak
                    ->placeholder('Contoh: 2024/2025') // Teks bayangan sebagai contoh
                    ->required(), // Wajib diisi

                // Pilihan Drop-down untuk jenis semester
                Select::make('semester')
                    ->label('Semester')
                    // Pilihan yang tersedia: angka 1 (ditampilkan sebagai '1') dan '2'
                    ->options([
                        1 => 'Ganjil (1)', 
                        2 => 'Genap (2)'
                    ])
                    ->required(),

                // Tombol geser On/Off untuk menandai apakah tahun ajaran ini sedang berjalan (aktif)
                Toggle::make('is_active')
                    ->label('Sedang Aktif?')
                    ->helperText('Hanya boleh ada 1 tahun ajaran yang aktif dalam satu waktu.')
                    ->required(),

                // Pemilih tanggal (Kalender) untuk menentukan kapan tahun ajaran ini mulai
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->native(false), // Menggunakan kalender pop-up bawaan Filament (bukan bawaan browser Chrome/Firefox)

                // Pemilih tanggal (Kalender) untuk menentukan kapan tahun ajaran ini berakhir
                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->native(false),
            ]);
    }
}
