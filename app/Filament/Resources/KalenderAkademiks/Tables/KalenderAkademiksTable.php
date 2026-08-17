<?php

// Lokasi folder

namespace App\Filament\Resources\KalenderAkademiks\Tables;

// Tombol & Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * KalenderAkademiksTable
 *
 * Mengatur kolom daftar acara pada Kalender Akademik.
 */
class KalenderAkademiksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // Judul Kegiatan
                TextColumn::make('judul')
                    ->label('Judul Kegiatan')
                    ->searchable(),

                // Tanggal Mulai
                TextColumn::make('tanggal_mulai')
                    ->label('Tanggal Mulai')
                    ->date('d M Y') // Format jadi rapi, misal: 12 Jan 2024
                    ->sortable(),

                // Tanggal Selesai
                TextColumn::make('tanggal_selesai')
                    ->label('Tanggal Selesai')
                    ->date('d M Y')
                    ->sortable(),

                // Tahun Ajaran
                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->searchable(),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Kosong
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            // Urutkan default, kegiatan yang paling dekat akan muncul paling atas
            ->defaultSort('tanggal_mulai', 'asc');
    }
}
