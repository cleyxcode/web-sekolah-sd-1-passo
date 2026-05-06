<?php

// Menentukan alamat folder tempat file ini berada
namespace App\Filament\Resources\Users\Schemas;

// Mengimpor elemen-elemen formulir dari sistem Filament (seperti kotak teks, tombol on/off)
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

/**
 * UserForm
 * 
 * Kelas ini mengatur susunan dan aturan pada kotak-kotak isian (Form)
 * saat menambahkan atau mengedit data Admin/Pengguna.
 */
class UserForm
{
    /**
     * Fungsi utama untuk menyusun formulir
     */
    public static function configure(Schema $schema): Schema
    {
        return $schema
            // ->components() digunakan untuk memasukkan komponen apa saja ke dalam form
            ->components([
                
                // Membuat kotak isian untuk teks biasa (untuk kolom 'name')
                TextInput::make('name')
                    ->label('Nama Lengkap') // Tulisan label yang muncul di atas kotak isian
                    ->required(),           // Kolom ini wajib diisi (tidak boleh kosong)

                // Membuat kotak isian untuk email
                TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()               // Memastikan teks yang diisi berformat email (ada simbol @)
                    ->required(),           // Wajib diisi

                // Membuat kotak isian untuk kata sandi (Password)
                TextInput::make('password')
                    ->label('Password')
                    ->password()            // Menyembunyikan huruf saat diketik (menjadi simbol bintang/titik)
                    // ->dehydrated() mengatur apakah password ini akan disimpan ke database?
                    // Hanya simpan password ke database JIKA kotak isian ini diisi (tidak dibiarkan kosong saat edit)
                    ->dehydrated(fn ($state) => filled($state))
                    // Kotak password WAJIB DIISI hanya jika sedang "membuat akun baru" (create),
                    // Jika sedang "mengedit akun" (edit), boleh dikosongkan (artinya password tidak diganti)
                    ->required(fn (string $context): bool => $context === 'create'),

                // Membuat tombol On/Off (Toggle) untuk kolom status aktif
                Toggle::make('is_active')
                    ->label('Status Aktif')
                    ->default(true)         // Nilai bawaan saat baru bikin adalah "ON" (true)
                    ->required(),

                // Membuat kotak isian pilihan drop-down untuk Peran (Role)
                // Diambil dari plugin Filament Spatie Roles & Permissions
                \Filament\Forms\Components\Select::make('roles')
                    ->label('Peran (Role)')
                    ->multiple()            // Satu pengguna boleh punya banyak peran (Admin, Guru, dll)
                    ->relationship('roles', 'name') // Mengambil daftar pilihan otomatis dari relasi tabel 'roles'
                    ->preload(),            // Langsung memuat pilihan ke dalam memori tanpa perlu ngetik/cari manual dulu
            ]);
    }
}
