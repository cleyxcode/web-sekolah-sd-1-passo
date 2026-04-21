<?php

namespace App\Filament\Resources\Galeris\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class GalerisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul')
                    ->searchable(),
                TextColumn::make('jenis')
                    ->label('Jenis')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'foto'  => 'info',
                        'video' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('user.name')
                    ->label('Pembuat')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('created_at')
                    ->label('Dibuat')
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
