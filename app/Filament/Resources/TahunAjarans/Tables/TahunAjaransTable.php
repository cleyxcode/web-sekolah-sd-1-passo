<?php

// Alamat folder tempat file ini berada

namespace App\Filament\Resources\TahunAjarans\Tables;

// Mengimpor elemen tombol aksi dan penyusun tabel
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * TahunAjaransTable
 *
 * Kelas ini mengatur daftar kolom yang akan ditampilkan
 * pada halaman tabel "Daftar Tahun Ajaran".
 */
class TahunAjaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->columns() mengatur kolom apa saja yang akan muncul
            ->columns([

                // Menampilkan nama tahun ajaran (contoh: 2024/2025)
                TextColumn::make('nama')
                    ->label('Nama Tahun Ajaran')
                    ->searchable() // Bisa dicari dari kotak pencarian
                    ->sortable()   // Bisa diklik untuk mengurutkan (A-Z)
                    ->weight('bold'), // Teks ditebalkan

                // Menampilkan angka semester
                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge() // Ditampilkan dalam bentuk kotak lencana (badge) yang rapi
                    // Mengubah tampilan angka 1 menjadi Ganjil, angka 2 menjadi Genap
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        '1' => 'Ganjil (1)',
                        '2' => 'Genap (2)',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        '1' => 'info',    // Biru muda
                        '2' => 'success', // Hijau
                        default => 'gray',
                    }),

                // Menampilkan ikon centang/silang untuk menandakan tahun ajaran ini aktif atau tidak
                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(), // Menggunakan gaya default centang hijau (true) dan silang merah (false)

                // Menampilkan tanggal kapan tahun ajaran ini dimulai
                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date('d M Y') // Format tanggal, contoh: "01 Jul 2024"
                    ->sortable(),

                // Menampilkan tanggal kapan tahun ajaran ini berakhir
                TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable(),

                // Menampilkan waktu pembuatan data ini (biasanya disembunyikan agar tidak menuhi layar)
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara bawaan

                // Menampilkan waktu terakhir data ini diedit
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            // ->filters() digunakan untuk menyaring data (Pojok Kanan Atas)
            ->filters([
                // Kosong untuk saat ini
            ])
            // ->recordActions() adalah tombol di ujung kanan setiap baris data
            ->recordActions([
                // Tombol untuk mengedit tahun ajaran
                EditAction::make(),
            ])
            // ->toolbarActions() adalah tombol aksi untuk beberapa baris sekaligus yang dicentang
            ->toolbarActions([
                BulkActionGroup::make([
                    // Tombol hapus banyak (massal)
                    DeleteBulkAction::make(),
                ]),
            ])
            // Mengurutkan data terbaru selalu ada di paling atas
            ->defaultSort('created_at', 'desc');
    }
}
