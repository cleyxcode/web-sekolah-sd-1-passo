<?php

// Menentukan alamat folder tempat file ini berada
namespace App\Filament\Resources\Users\Tables;

// Mengimpor tombol aksi dan kolom-kolom untuk membuat tampilan tabel
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * UsersTable
 * 
 * Kelas ini mengatur susunan kolom apa saja yang akan ditampilkan 
 * pada daftar tabel data Admin/Pengguna di halaman utama menu ini.
 */
class UsersTable
{
    /**
     * Fungsi utama untuk menyusun tabel
     */
    public static function configure(Table $table): Table
    {
        return $table
            // ->columns() digunakan untuk mendaftarkan kolom apa saja yang muncul di tabel
            ->columns([
                
                // Menampilkan nama lengkap menggunakan teks biasa
                TextColumn::make('name')
                    ->label('Nama Lengkap') // Judul kolom di tabel (header)
                    ->searchable(),         // Data pada kolom ini bisa dicari melalui kotak pencarian (Search box)

                // Menampilkan alamat email
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),         // Bisa dicari

                // Menampilkan nama peran (role) milik user tersebut
                TextColumn::make('roles.name')
                    ->label('Peran (Role)')
                    ->badge()               // Tampilannya dibuat berbentuk lencana (badge) yang keren, bukan cuma teks biasa
                    ->searchable(),         // Bisa dicari

                // Menampilkan status aktif dengan menggunakan simbol ikon Centang/Silang
                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),            // Tampilkan ikon hijau (centang) jika true, silang (merah) jika false

                // Menampilkan informasi waktu kapan data ini pertama kali dibuat
                TextColumn::make('created_at')
                    ->dateTime()            // Tampilkan dalam format tanggal & waktu
                    ->sortable()            // Kolom ini bisa diklik untuk mengurutkan (sort) data terbaru/terlama
                    ->toggleable(isToggledHiddenByDefault: true), // Kolom ini disembunyikan secara bawaan (bisa ditampilkan via menu pengaturan kolom)

                // Menampilkan informasi waktu kapan data ini terakhir diubah
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Juga disembunyikan secara bawaan
            ])
            // ->filters() digunakan untuk menyaring data (misal: filter khusus user aktif saja)
            ->filters([
                // Kosong untuk saat ini
            ])
            // ->recordActions() adalah tombol aksi yang ada di ujung KANAN pada SETIAP BARIS tabel
            ->recordActions([
                // Tambahkan tombol untuk mengedit data pada baris tersebut
                EditAction::make(),
            ])
            // ->toolbarActions() adalah tombol aksi untuk beberapa baris sekaligus yang dicentang (Bulk action)
            ->toolbarActions([
                // Kumpulkan tombol aksi massal ke dalam satu grup drop-down
                BulkActionGroup::make([
                    // Tambahkan tombol untuk menghapus banyak data sekaligus
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
