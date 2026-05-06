<?php

// Lokasi folder
namespace App\Filament\Resources\SettingSekolahs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

/**
 * SettingSekolahsTable
 * 
 * Karena SettingSekolahResource dibuat langsung diarahkan ke halaman Edit (Index diarahkan ke Edit),
 * file tabel ini sebenarnya jarang tereksekusi. Namun tetap disiapkan sebagai cadangan jika 
 * pengaturan sekolah ingin ditampilkan dalam format daftar tabel.
 */
class SettingSekolahsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_sekolah')
                    ->searchable(),
                TextColumn::make('logo')
                    ->searchable(),
                TextColumn::make('no_telepon')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Alamat Email')
                    ->searchable(),
                TextColumn::make('instagram')
                    ->searchable(),
                TextColumn::make('facebook')
                    ->searchable(),
                TextColumn::make('youtube')
                    ->searchable(),
                TextColumn::make('website')
                    ->searchable(),
                TextColumn::make('kepala_sekolah')
                    ->searchable(),
                TextColumn::make('npsn')
                    ->searchable(),
                TextColumn::make('akreditasi')
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
                //
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
