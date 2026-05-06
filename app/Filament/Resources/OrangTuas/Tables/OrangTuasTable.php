<?php

// Lokasi folder
namespace App\Filament\Resources\OrangTuas\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * OrangTuasTable
 * 
 * Mengatur kolom yang ditampilkan pada halaman daftar orang tua.
 */
class OrangTuasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama')
                    ->searchable(),
                
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),
                
                TextColumn::make('no_telepon')
                    ->searchable(),
                
                TextColumn::make('pekerjaan')
                    ->searchable(),
                
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
