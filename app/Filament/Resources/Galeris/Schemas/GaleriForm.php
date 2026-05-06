<?php

// Lokasi folder
namespace App\Filament\Resources\Galeris\Schemas;

// Form
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

/**
 * GaleriForm
 * 
 * Mengatur susunan kotak isian saat Admin mau mengunggah foto/video ke Galeri.
 */
class GaleriForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // Judul Foto/Video (Misal: Kegiatan Lomba 17 Agustus)
                TextInput::make('judul')
                    ->label('Judul / Nama Dokumentasi')
                    ->required(),
                
                // Kotak Upload File
                FileUpload::make('file_path')
                    ->label('File Foto / Video')
                    ->directory('galeri') // Disimpan di folder "galeri" di dalam folder "storage"
                    ->acceptedFileTypes(['image/*', 'video/*']) // Hanya boleh gambar atau video
                    ->maxSize(10240) // Batas maksimal ukuran file: 10 MB (10240 KB)
                    ->helperText('Upload foto atau video. Maksimal 10 MB.')
                    ->required(),
                
                // Pilihan Jenis File (agar website tahu cara menampilkannya)
                Select::make('jenis')
                    ->label('Jenis File')
                    ->options(['foto' => 'Foto', 'video' => 'Video'])
                    ->default('foto')
                    ->required(),
                
                // Keterangan teks tambahan di bawah foto
                Textarea::make('keterangan')
                    ->label('Keterangan / Cerita (Opsional)')
                    ->columnSpanFull(),
                
                // Otomatis mencatat siapa yang meng-upload gambar ini, 
                // tapi disembunyikan (Hidden) agar tidak kelihatan di layar
                \Filament\Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }
}
