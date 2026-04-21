<?php

namespace App\Filament\Resources\Rapors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RaporsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa_id')
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
                TextColumn::make('total_hadir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sakit')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_izin')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_alpha')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('generated_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('file_path')
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
