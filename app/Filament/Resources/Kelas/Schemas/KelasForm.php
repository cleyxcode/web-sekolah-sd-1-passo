<?php

// Lokasi folder

namespace App\Filament\Resources\Kelas\Schemas;

// Model
use App\Models\Guru;
use App\Models\TahunAjaran;
// Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * KelasForm
 *
 * Mengatur susunan isian saat membuat rombongan belajar (Kelas) baru.
 */
class KelasForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([

            // Nama panggilan kelas (Contoh: 1A, 1B, Mawar, Melati)
            TextInput::make('nama_kelas')
                ->label('Nama Kelas')
                ->placeholder('Contoh: 1A, 2B, 3A')
                ->required()
                ->maxLength(20)
                ->columnSpan(1), // Kotak berukuran setengah lebar layar

            // Penentuan tingkat kelas (1 SD - 6 SD)
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

            // Memilih kelas ini berlaku untuk tahun ajaran kapan
            Select::make('tahun_ajaran_id')
                ->label('Tahun Ajaran')
                ->relationship('tahunAjaran', 'nama') // Ambil data dari tabel Tahun Ajaran
                ->required()
                ->searchable()
                ->preload()
                // Otomatis mencari dan memilih tahun ajaran yang saat ini sedang aktif (is_active = true)
                ->default(fn () => TahunAjaran::where('is_active', true)->first()?->id)
                ->columnSpan(1),

            // Memilih Guru mana yang ditugaskan memegang (wali) kelas ini
            Select::make('wali_kelas_id')
                ->label('Wali Kelas')
                ->relationship('waliKelas', 'nama') // Ambil data dari tabel Guru
                ->searchable()
                ->preload()
                ->placeholder('Pilih guru wali kelas...') // Kosongkan jika belum ada wali
                ->columnSpan(1),

        ])->columns(2); // Menampilkan isian dalam 2 kolom bersebelahan
    }
}
