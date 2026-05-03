<?php

namespace App\Filament\Resources\Gurus\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Akun Login')
                    ->description('Data akun untuk login ke sistem.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('email')
                                ->label('Email Akun')
                                ->email()
                                ->required()
                                ->unique(table: 'users', column: 'email', ignorable: fn ($record) => $record?->user),
                            TextInput::make('password')
                                ->label('Password Akun')
                                ->password()
                                ->revealable()
                                ->required(fn ($livewire) => $livewire instanceof \Filament\Resources\Pages\CreateRecord)
                                ->minLength(6)
                                ->dehydrated(fn ($state) => filled($state))
                                ->helperText('Kosongkan saat edit jika tidak ingin mengubah password.'),
                        ]),
                    ]),

                Section::make('Data Pribadi')
                    ->description('Informasi pribadi guru.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('nip')
                                ->label('NIP')
                                ->helperText('Nomor Induk Pegawai (opsional).'),
                            TextInput::make('nama')
                                ->label('Nama Lengkap')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([
                            Select::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                                ->required()
                                ->native(false),
                            TextInput::make('no_telepon')
                                ->label('No. Telepon')
                                ->tel(),
                        ]),
                        TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->placeholder('Contoh: Kepala Sekolah, Wali Kelas 1A, Guru Matematika')
                            ->helperText('Jabatan yang akan ditampilkan di website sekolah.'),
                        FileUpload::make('foto')
                            ->label('Foto Profil')
                            ->image()
                            ->automaticallyResizeImagesMode('cover')
                            ->imageAspectRatio('1:1')
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesToWidth(400)
                            ->automaticallyResizeImagesToHeight(400)
                            ->directory('foto-guru')
                            ->visibility('public')
                            ->helperText('Upload foto guru (format JPG/PNG, rasio 1:1 untuk hasil terbaik).'),
                    ]),

                Section::make('Pengaturan Tampilan Website')
                    ->description('Atur apakah guru ini ditampilkan di halaman publik sekolah.')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Grid::make(2)->schema([
                            Toggle::make('tampil_di_website')
                                ->label('Tampilkan di Website')
                                ->helperText('Aktifkan agar foto dan profil tampil di halaman publik sekolah.')
                                ->default(true),
                            TextInput::make('urutan_tampil')
                                ->label('Urutan Tampil')
                                ->numeric()
                                ->minValue(1)
                                ->default(99)
                                ->helperText('Angka lebih kecil = tampil lebih dulu. Kepala Sekolah biasanya urutan 1.'),
                        ]),
                    ]),
            ]);
    }
}
