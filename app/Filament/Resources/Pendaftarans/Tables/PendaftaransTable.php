<?php

// Lokasi folder
namespace App\Filament\Resources\Pendaftarans\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * PendaftaransTable
 * 
 * Mengatur kolom yang ditampilkan pada halaman daftar Pendaftaran Siswa.
 */
class PendaftaransTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                // Judul Pendaftaran
                TextColumn::make('judul')
                    ->label('Judul Pendaftaran')
                    ->searchable(),
                
                // Link pendaftaran
                TextColumn::make('link_pendaftaran')
                    ->label('Link URL')
                    // Jika teks ini diklik, buka link URL aslinya
                    ->url(fn ($record) => $record->link_pendaftaran)
                    ->openUrlInNewTab() // Buka link di tab baru pada browser (tidak menimpa tab admin)
                    ->color('primary')  // Beri warna biru agar terlihat seperti link bisa diklik
                    ->searchable(),
                
                // Status Aktif (Berupa Ikon Centang / Silang)
                IconColumn::make('is_active')
                    ->label('Status Aktif')
                    ->boolean(),
                
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
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
