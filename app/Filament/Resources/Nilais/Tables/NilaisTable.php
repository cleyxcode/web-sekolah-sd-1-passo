<?php

namespace App\Filament\Resources\Nilais\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Kelas;

class NilaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->kelas?->nama_kelas
                        ? 'Kelas: ' . $record->kelas->nama_kelas
                        : null),

                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('mataPelajaran.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('jenis_ujian')
                    ->label('Jenis Ujian')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'UTS' => 'warning',
                        'UAS' => 'success',
                        default => 'gray',
                    }),

                TextColumn::make('nilai_angka')
                    ->label('Nilai')
                    ->numeric(decimalPlaces: 1)
                    ->sortable()
                    ->badge()
                    ->color(fn ($state): string => match (true) {
                        $state >= 85 => 'success',
                        $state >= 70 => 'warning',
                        default      => 'danger',
                    }),

                TextColumn::make('guru.nama')
                    ->label('Guru Pengajar')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->options(
                        Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                            ->get()
                            ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas}"])
                    )
                    ->searchable(),

                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options(['1' => 'Semester 1', '2' => 'Semester 2']),

                SelectFilter::make('jenis_ujian')
                    ->label('Jenis Ujian')
                    ->options(['UTS' => 'UTS', 'UAS' => 'UAS']),

                SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama')
                    ->searchable()
                    ->preload(),
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
            ->defaultSort('created_at', 'desc');
    }
}
