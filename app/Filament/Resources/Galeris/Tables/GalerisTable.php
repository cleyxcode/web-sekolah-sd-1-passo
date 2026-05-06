<?php

// Lokasi folder
namespace App\Filament\Resources\Galeris\Tables;

// Tombol & Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * GalerisTable
 * 
 * Mengatur kolom daftar galeri dokumentasi sekolah.
 */
class GalerisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                // Judul Gambar
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable(),
                
                // Jenis (Foto / Video)
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge() // Bentuk lencana
                    // Warnai biru untuk foto, hijau untuk video
                    ->color(fn (string $state): string => match ($state) {
                        'foto'  => 'info',
                        'video' => 'success',
                        default => 'gray',
                    }),
                
                // Pembuat (Nama admin yang upload)
                TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
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
            ]);
    }
}
