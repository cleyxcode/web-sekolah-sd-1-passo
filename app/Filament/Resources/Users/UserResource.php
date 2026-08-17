<?php

// Menentukan alamat folder tempat file ini berada

namespace App\Filament\Resources\Users;

// Mengimpor file-file kelas halaman (Pages) untuk resource User ini
use App\Filament\Resources\Users\Pages\CreateUser; // Halaman tambah data
use App\Filament\Resources\Users\Pages\EditUser;   // Halaman edit data
use App\Filament\Resources\Users\Pages\ListUsers;  // Halaman daftar tabel
// Mengimpor konfigurasi form dan tabel yang sudah dipisah ke file lain
use App\Filament\Resources\Users\Schemas\UserForm;
use App\Filament\Resources\Users\Tables\UsersTable;
// Mengimpor model User dari database
use App\Models\User;
// Kelas bawaan untuk keperluan pembuatan antarmuka (UI) di Filament
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

/**
 * UserResource
 *
 * Kelas ini adalah penghubung utama antara database tabel 'users'
 * dengan tampilan antarmuka (Dashboard) Filament Admin.
 */
class UserResource extends Resource
{
    // Menentukan model database mana yang dikelola oleh resource ini
    protected static ?string $model = User::class;

    // Menentukan ikon menu yang muncul di sidebar sebelah kiri (Heroicon Outlined Users)
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;

    // Mengelompokkan menu ini ke dalam grup "Pengaturan Pengguna" di sidebar
    protected static string|\UnitEnum|null $navigationGroup = 'Pengaturan Pengguna';

    // Label tunggal untuk data ini (misal di tombol: "Tambah Admin")
    protected static ?string $modelLabel = 'Admin';

    // Label jamak untuk daftar data ini (misal judul halaman: "Admin")
    protected static ?string $pluralModelLabel = 'Admin';

    /**
     * Konfigurasi Formulir (Form)
     * Apa saja kotak isian yang muncul saat tambah/edit data?
     */
    public static function form(Schema $schema): Schema
    {
        // Alihkan konfigurasi formulir ke file UserForm.php agar kode lebih rapi
        return UserForm::configure($schema);
    }

    /**
     * Konfigurasi Tabel
     * Apa saja kolom data yang muncul di halaman daftar data?
     */
    public static function table(Table $table): Table
    {
        // Alihkan konfigurasi tabel ke file UsersTable.php agar kode lebih rapi
        return UsersTable::configure($table);
    }

    /**
     * Konfigurasi Relasi Manager
     * Digunakan jika ingin menampilkan tabel anak di dalam halaman edit bapak (contoh: daftar postingan milik user)
     */
    public static function getRelations(): array
    {
        return [
            // Dibiarkan kosong karena belum ada tabel relasi tambahan yang dimunculkan
        ];
    }

    /**
     * Konfigurasi Halaman (Pages)
     * Mengatur alamat link (URL) untuk setiap halaman pada menu ini.
     */
    public static function getPages(): array
    {
        return [
            // Halaman daftar tabel berada di URL dasar resource ini ( / )
            'index' => ListUsers::route('/'),
            // Halaman tambah data berada di ( /create )
            'create' => CreateUser::route('/create'),
            // Halaman edit data khusus diletakkan di rute dengan parameter ID ( /{record}/edit )
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
