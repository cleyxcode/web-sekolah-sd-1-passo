<?php

namespace App\Filament\Resources\Tugas\Tables;

use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

class TugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('judul')
                    ->label('Judul Tugas')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->description(fn($record) => $record->mata_pelajaran ?? '—'),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('guru.nama')
                    ->label('Guru Pemberi')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->dateTime('d M Y, H:i')
                    ->sortable()
                    ->color(fn($record) => $record->deadline->isPast() ? 'danger' : 'success')
                    ->description(fn($record) => $record->sisa_waktu),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match($state) {
                        'aktif'      => 'success',
                        'selesai'    => 'info',
                        'dibatalkan' => 'danger',
                        default      => 'gray',
                    })
                    ->formatStateUsing(fn(string $state): string => match($state) {
                        'aktif'      => 'Aktif',
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default      => $state,
                    }),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas'),

                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'aktif'      => 'Aktif',
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->defaultSort('deadline', 'asc')
            ->emptyStateHeading('Belum ada tugas')
            ->emptyStateDescription('Klik tombol "Buat Tugas" untuk menambahkan tugas baru.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }
}
