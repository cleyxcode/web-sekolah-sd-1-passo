<?php

// Lokasi folder

namespace App\Filament\Resources\Gurus\Schemas;

// Elemen form
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

/**
 * GuruForm
 *
 * Mengatur susunan kotak isian saat Admin menambahkan data guru baru.
 * Otomatis juga berfungsi ganda untuk membuatkan akun Users.
 */
class GuruForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // Bagian 1: Akun Login
                Section::make('Akun Login')
                    ->description('Data akun untuk login ke sistem.')
                    ->icon('heroicon-o-lock-closed')
                    ->schema([
                        Grid::make(2)->schema([

                            // Email (User)
                            TextInput::make('email')
                                ->label('Email Akun')
                                ->email()
                                ->required()
                                // Email harus unik, dicek ke tabel 'users' bukan tabel 'gurus'
                                ->unique(table: 'users', column: 'email', ignorable: fn ($record) => $record?->user),

                            // Password (User)
                            TextInput::make('password')
                                ->label('Password Akun')
                                ->password()
                                ->revealable()
                                // Hanya wajib diisi saat baru buat pertama kali
                                ->required(fn ($livewire) => $livewire instanceof CreateRecord)
                                ->minLength(6)
                                ->dehydrated(fn ($state) => filled($state))
                                ->helperText('Kosongkan saat edit jika tidak ingin mengubah password.'),
                        ]),
                    ]),

                // Bagian 2: Data Diri Guru
                Section::make('Data Pribadi')
                    ->description('Informasi pribadi guru.')
                    ->icon('heroicon-o-user')
                    ->schema([
                        Grid::make(2)->schema([

                            // NIP
                            TextInput::make('nip')
                                ->label('NIP')
                                ->helperText('Nomor Induk Pegawai (opsional).'),

                            // Nama Asli
                            TextInput::make('nama')
                                ->label('Nama Lengkap')
                                ->required(),
                        ]),
                        Grid::make(2)->schema([

                            // Jenis Kelamin
                            Select::make('jenis_kelamin')
                                ->label('Jenis Kelamin')
                                ->options(['L' => 'Laki-laki', 'P' => 'Perempuan'])
                                ->required()
                                ->native(false),

                            // Telepon
                            TextInput::make('no_telepon')
                                ->label('No. Telepon')
                                ->tel(),
                        ]),

                        // Jabatan
                        TextInput::make('jabatan')
                            ->label('Jabatan')
                            ->placeholder('Contoh: Kepala Sekolah, Wali Kelas 1A, Guru Matematika')
                            ->helperText('Jabatan yang akan ditampilkan di website sekolah.'),

                        // FITUR CANGGIH: Foto Guru
                        FileUpload::make('foto')
                            ->label('Foto Profil')
                            ->image() // Hanya terima gambar
                            // --- SISTEM OTOMATIS MEMOTONG GAMBAR ---
                            ->automaticallyResizeImagesMode('cover')
                            ->imageAspectRatio('1:1') // Otomatis dipotong jadi kotak persegi sempurna
                            ->automaticallyCropImagesToAspectRatio()
                            ->automaticallyResizeImagesToWidth(400) // Ukuran resolusi dimampatkan jadi 400x400
                            ->automaticallyResizeImagesToHeight(400)
                            ->directory('foto-guru') // Disimpan di folder khusus guru
                            ->visibility('public')
                            ->helperText('Upload foto guru (format JPG/PNG, rasio 1:1 untuk hasil terbaik).'),
                    ]),

                // Bagian 3: Urutan di Website Publik
                Section::make('Pengaturan Tampilan Website')
                    ->description('Atur apakah guru ini ditampilkan di halaman publik sekolah.')
                    ->icon('heroicon-o-globe-alt')
                    ->schema([
                        Grid::make(2)->schema([

                            // Tampilkan / Sembunyikan
                            Toggle::make('tampil_di_website')
                                ->label('Tampilkan di Website')
                                ->helperText('Aktifkan agar foto dan profil tampil di halaman publik sekolah.')
                                ->default(true),

                            // Angka antrian
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
