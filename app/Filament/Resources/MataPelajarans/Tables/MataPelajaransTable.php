<?php

// Lokasi folder

namespace App\Filament\Resources\MataPelajarans\Tables;

// Tombol & Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * MataPelajaransTable
 *
 * Mengatur daftar kolom tabel pada halaman Mata Pelajaran.
 */
class MataPelajaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Menampilkan nama pelajaran
                TextColumn::make('nama')
                    ->label('Nama Mata Pelajaran')
                    ->searchable(),

                // Menampilkan kode (singkatan)
                TextColumn::make('kode')
                    ->label('Kode')
                    ->badge() // Bentuk lencana
                    ->searchable(),

                // Menampilkan tingkat kelas
                TextColumn::make('tingkat_kelas')
                    ->label('Khusus Kelas')
                    ->numeric()
                    ->sortable()
                    ->placeholder('Semua Kelas'), // Kalau kosong di database, tampilkan teks ini

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Kosong
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            // Urutkan default berdasarkan tingkat kelas dari terendah ke tertinggi
            ->defaultSort('tingkat_kelas', 'asc');
    }
}
