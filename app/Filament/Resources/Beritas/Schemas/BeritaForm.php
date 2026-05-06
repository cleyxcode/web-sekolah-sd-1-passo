<?php

// Lokasi folder
namespace App\Filament\Resources\Beritas\Schemas;

// Form
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Str; // Bawaan Laravel untuk mengubah teks

/**
 * BeritaForm
 * 
 * Mengatur alat-alat menulis (seperti Microsoft Word) saat menulis berita.
 */
class BeritaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                
                // Judul Berita
                TextInput::make('judul')
                    ->label('Judul Berita')
                    ->required()
                    ->live(onBlur: true) // Bereaksi ketika selesai diketik
                    // OTOMATIS membuat SLUG (Link URL) saat judul diketik
                    ->afterStateUpdated(fn (string $operation, $state, callable $set) => 
                        $operation === 'create' ? $set('slug', Str::slug($state)) : null),

                // Link URL Berita (Slug) - Contoh: website.com/berita/lomba-mewarnai
                TextInput::make('slug')
                    ->label('Link URL Berita (Slug)')
                    ->required()
                    ->unique(ignoreRecord: true), // Link URL tidak boleh sama dengan berita lain

                // Kotak Teks Besar (Bisa tebal, miring, kasih gambar, dll)
                RichEditor::make('isi')
                    ->label('Isi Berita')
                    ->required()
                    ->columnSpanFull(),

                // Gambar sampul berita (Thumbnail)
                FileUpload::make('foto')
                    ->label('Gambar Sampul (Thumbnail)')
                    ->image()
                    ->directory('berita') // Simpan di folder berita
                    ->maxSize(5120), // Maksimal 5 MB
                
                // Jenis / Topik Berita
                TextInput::make('kategori')
                    ->label('Kategori')
                    ->placeholder('Contoh: Pengumuman, Prestasi, Ekstrakurikuler'),

                // Status apakah berita sudah mau diterbitkan ke publik atau mau disimpan dulu
                Select::make('status')
                    ->label('Status Berita')
                    ->options([
                        'draft'   => 'Draft (Simpan Sementara)', 
                        'publish' => 'Publish (Terbitkan ke Publik)'
                    ])
                    ->default('draft')
                    ->required(),

                // Jam dan Tanggal berita dipublish
                DateTimePicker::make('published_at')
                    ->label('Tanggal Terbit')
                    ->default(now()), // Otomatis hari ini

                // Pencatat diam-diam siapa penulisnya
                \Filament\Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }
}
