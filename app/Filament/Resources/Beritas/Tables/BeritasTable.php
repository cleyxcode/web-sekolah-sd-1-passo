<?php

// Lokasi folder
namespace App\Filament\Resources\Beritas\Tables;

// Kolom & Tombol
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * BeritasTable
 * 
 * Mengatur kolom tabel daftar berita yang sudah ditulis.
 */
class BeritasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable(),
                
                TextColumn::make('kategori')
                    ->label('Kategori')
                    ->searchable()
                    ->placeholder('-'),
                
                // Status (Publish / Draft)
                TextColumn::make('status')
                    ->label('Status')
                    ->badge() // Berbentuk lencana
                    // Warnai hijau jika publish, kuning jika draft
                    ->color(fn (string $state): string => match ($state) {
                        'publish' => 'success',
                        'draft'   => 'warning',
                        default   => 'gray',
                    }),
                
                // Penulis Asli
                TextColumn::make('user.name')
                    ->label('Penulis')
                    ->sortable()
                    ->searchable(),
                
                TextColumn::make('published_at')
                    ->label('Tanggal Publish')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
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
            // Urutkan berita berdasarkan tanggal publish terbaru
            ->defaultSort('published_at', 'desc');
    }
}
