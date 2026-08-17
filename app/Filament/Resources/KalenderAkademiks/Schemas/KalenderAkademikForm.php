<?php

// Lokasi folder

namespace App\Filament\Resources\KalenderAkademiks\Schemas;

// Form
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

/**
 * KalenderAkademikForm
 *
 * Mengatur kotak isian saat membuat pengumuman jadwal di kalender.
 */
class KalenderAkademikForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Judul Acara (Misal: Ujian Akhir Semester)
                TextInput::make('judul')
                    ->label('Judul Kegiatan')
                    ->required(),

                // Tanggal Mulai
                DatePicker::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->required(),

                // Tanggal Selesai (Bisa sama dengan tanggal mulai jika acara 1 hari saja)
                DatePicker::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->required(),

                // Keterangan tambahan (Opsional)
                Textarea::make('keterangan')
                    ->label('Keterangan Tambahan')
                    ->columnSpanFull(),

                // Pilihan Tahun Ajaran terkait acara ini
                Select::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama')
                    ->required()
                    ->searchable()
                    ->preload(),
            ]);
    }
}
