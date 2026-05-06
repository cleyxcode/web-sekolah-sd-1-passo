<?php

// Lokasi folder
namespace App\Filament\Resources\JadwalPelajarans\Tables;

// Tombol & Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * JadwalPelajaransTable
 * 
 * Mengatur tabel jadwal pelajaran yang ditampilkan ke layar Admin.
 */
class JadwalPelajaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                // Kelas yang belajar
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable()
                    ->sortable()
                    ->badge(), // Tampilkan dengan bingkai lencana
                
                // Mata Pelajaran
                TextColumn::make('mataPelajaran.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),
                
                // Guru Pengajar
                TextColumn::make('guru.nama')
                    ->label('Guru Pengajar')
                    ->searchable()
                    ->sortable(),
                
                // Hari
                TextColumn::make('hari')
                    ->label('Hari')
                    ->searchable(),
                
                // Jam Mulai
                TextColumn::make('jam_mulai')
                    ->label('Jam Mulai')
                    ->time('H:i') // Tampilkan format jam dan menit saja (Contoh: 07:30)
                    ->sortable(),
                
                // Jam Selesai
                TextColumn::make('jam_selesai')
                    ->label('Jam Selesai')
                    ->time('H:i')
                    ->sortable(),
                
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
            ]);
    }
}
