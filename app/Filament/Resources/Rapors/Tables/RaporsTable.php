<?php

namespace App\Filament\Resources\Rapors\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RaporsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft'    => 'warning',
                        'final'    => 'success',
                        default    => 'gray',
                    }),
                TextColumn::make('total_hadir')
                    ->label('Hadir')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('generated_at')
                    ->label('Digenerate')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
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
