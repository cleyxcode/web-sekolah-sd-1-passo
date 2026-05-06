<?php

// Lokasi folder
namespace App\Filament\Resources\SettingSekolahs\Schemas;

// Komponen form
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * SettingSekolahForm
 * 
 * Mengatur susunan kotak isian untuk pengaturan identitas sekolah.
 */
class SettingSekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Bagian 1: Identitas Dasar
                Section::make('Identitas Sekolah')
                    ->description('Informasi dasar sekolah.')
                    ->icon('heroicon-o-building-office-2') // Ikon gedung
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_sekolah')
                                ->label('Nama Sekolah')
                                ->required(),
                            TextInput::make('npsn')
                                ->label('NPSN (Nomor Pokok Sekolah Nasional)'),
                        ]),
                        TextInput::make('kepala_sekolah')
                            ->label('Kepala Sekolah'),
                        Textarea::make('alamat')
                            ->label('Alamat Lengkap Sekolah')
                            ->columnSpanFull(),
                    ]),

                // Bagian 2: Gambar & Visual
                Section::make('Foto & Logo')
                    ->description('Upload logo dan foto utama (hero) halaman beranda.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make(2)->schema([
                            
                            // Kotak upload untuk logo sekolah (muncul di kop surat & header website)
                            FileUpload::make('logo')
                                ->label('Logo Sekolah')
                                ->image() // Hanya terima gambar
                                ->imagePreviewHeight('150') // Tinggi gambar preview (pratinjau) di layar
                                ->directory('logo-sekolah') // Disimpan di folder logo-sekolah
                                ->visibility('public')      // Agar bisa diakses dari luar oleh umum
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                ->maxSize(2048) // Maks 2 MB
                                ->helperText('Format: JPG, PNG, SVG, WebP. Maks 2MB.'),

                            // Kotak upload foto sampul/banner website
                            FileUpload::make('foto_hero')
                                ->label('Foto Hero (Halaman Beranda)')
                                ->image()
                                ->imagePreviewHeight('150')
                                ->directory('hero-sekolah')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(5120) // Maks 5 MB
                                ->helperText('Foto ini tampil di bagian utama halaman beranda. Format: JPG, PNG, WebP. Maks 5MB.'),
                        ]),
                    ]),

                // Bagian 3: Info Kontak
                Section::make('Kontak')
                    ->description('Informasi kontak sekolah.')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('no_telepon')
                                ->label('No. Telepon')
                                ->tel(), // Format isian telepon
                            TextInput::make('email')
                                ->label('Email Sekolah')
                                ->email(),
                        ]),
                        TextInput::make('website')
                            ->label('Website Resmi')
                            ->url() // Validasi format link URL
                            ->prefix('https://'), // Diberi awalan tetap agar user tidak perlu mengetik https
                    ]),

                // Bagian 4: Link Sosial Media
                Section::make('Media Sosial')
                    ->description('Akun media sosial sekolah.')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('instagram')
                                ->label('Instagram')
                                ->prefix('@'), // Awalan '@'
                            TextInput::make('facebook')
                                ->label('Facebook'),
                            TextInput::make('youtube')
                                ->label('YouTube'),
                        ]),
                    ]),

            ]);
    }
}
