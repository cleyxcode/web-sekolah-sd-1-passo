<?php

namespace App\Filament\Resources\SettingSekolahs\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SettingSekolahForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Identitas Sekolah')
                    ->description('Informasi dasar sekolah.')
                    ->icon('heroicon-o-building-office-2')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nama_sekolah')
                                ->label('Nama Sekolah')
                                ->required(),
                            TextInput::make('npsn')
                                ->label('NPSN'),
                        ]),
                        TextInput::make('kepala_sekolah')
                            ->label('Kepala Sekolah'),
                        Textarea::make('alamat')
                            ->label('Alamat Sekolah')
                            ->columnSpanFull(),
                    ]),

                Section::make('Foto & Logo')
                    ->description('Upload logo dan foto utama (hero) halaman beranda.')
                    ->icon('heroicon-o-photo')
                    ->schema([
                        Grid::make(2)->schema([
                            FileUpload::make('logo')
                                ->label('Logo Sekolah')
                                ->image()
                                ->imagePreviewHeight('150')
                                ->directory('logo-sekolah')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                                ->maxSize(2048)
                                ->helperText('Format: JPG, PNG, SVG, WebP. Maks 2MB.'),

                            FileUpload::make('foto_hero')
                                ->label('Foto Hero (Halaman Beranda)')
                                ->image()
                                ->imagePreviewHeight('150')
                                ->directory('hero-sekolah')
                                ->visibility('public')
                                ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp'])
                                ->maxSize(5120)
                                ->helperText('Foto ini tampil di bagian utama halaman beranda. Format: JPG, PNG, WebP. Maks 5MB.'),
                        ]),
                    ]),

                Section::make('Kontak')
                    ->description('Informasi kontak sekolah.')
                    ->icon('heroicon-o-phone')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('no_telepon')
                                ->label('No. Telepon')
                                ->tel(),
                            TextInput::make('email')
                                ->label('Email')
                                ->email(),
                        ]),
                        TextInput::make('website')
                            ->label('Website')
                            ->url()
                            ->prefix('https://'),
                        TextInput::make('link_ppdb')
                            ->label('Link PPDB')
                            ->url()
                            ->prefix('https://'),
                    ]),

                Section::make('Media Sosial')
                    ->description('Akun media sosial sekolah.')
                    ->icon('heroicon-o-share')
                    ->schema([
                        Grid::make(3)->schema([
                            TextInput::make('instagram')
                                ->label('Instagram')
                                ->prefix('@'),
                            TextInput::make('facebook')
                                ->label('Facebook'),
                            TextInput::make('youtube')
                                ->label('YouTube'),
                        ]),
                    ]),

            ]);
    }
}
