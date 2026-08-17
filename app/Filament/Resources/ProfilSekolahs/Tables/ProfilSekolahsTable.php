<?php

// Lokasi folder

namespace App\Filament\Resources\ProfilSekolahs\Tables;

// Tombol dan Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * ProfilSekolahsTable
 *
 * Mengatur tampilan daftar informasi Visi, Misi, Sejarah di halaman depan menu.
 */
class ProfilSekolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Menampilkan kolom jenis (Visi, Misi, dll) dengan bentuk lencana
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge(), // Bentuk lencana

                // Menampilkan judul tulisan
                TextColumn::make('judul')
                    ->label('Judul Tulisan')
                    ->searchable(), // Bisa dicari

                // Waktu data dibuat
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // Waktu data terakhir diedit
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Kosong
            ])
            ->recordActions([
                // Tombol Edit
                EditAction::make(),
            ])
            ->toolbarActions([
                // Tombol Hapus Massal
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
