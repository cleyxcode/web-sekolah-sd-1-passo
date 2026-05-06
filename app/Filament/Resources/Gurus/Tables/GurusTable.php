<?php

// Lokasi folder
namespace App\Filament\Resources\Gurus\Tables;

// Kolom dan Tombol
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

/**
 * GurusTable
 * 
 * Mengatur kolom yang muncul di halaman daftar tabel guru.
 */
class GurusTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                // Menampilkan nama di tabel Users yang terhubung
                TextColumn::make('user.name')
                    ->label('Akun Pengguna')
                    ->sortable()
                    ->searchable(),
                
                // Menampilkan foto
                ImageColumn::make('foto')
                    ->label('Foto')
                    ->circular(), // Dibuat menjadi bundar (lingkaran) agar manis
                
                TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable()
                    ->placeholder('-'),
                
                TextColumn::make('nama')
                    ->label('Nama Lengkap')
                    ->searchable(),
                
                TextColumn::make('jabatan')
                    ->label('Jabatan')
                    ->searchable()
                    ->placeholder('-'),
                
                TextColumn::make('jenis_kelamin')
                    ->label('J.Kelamin')
                    ->badge(),
                
                TextColumn::make('no_telepon')
                    ->label('Telepon')
                    ->searchable()
                    ->placeholder('-'),
                
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
