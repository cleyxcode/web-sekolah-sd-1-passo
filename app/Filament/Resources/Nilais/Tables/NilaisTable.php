<?php

namespace App\Filament\Resources\Nilais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class NilaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('mata_pelajaran_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('guru_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kelas_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('tahun_ajaran_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('semester')
                    ->badge(),
                TextColumn::make('jenis_ujian')
                    ->badge(),
                TextColumn::make('nilai_angka')
                    ->numeric()
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
