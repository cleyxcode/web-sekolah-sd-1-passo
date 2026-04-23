<?php

namespace App\Filament\Resources\CatatanPerkembangans\Tables;

use App\Models\Kelas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CatatanPerkembangansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->siswa?->kelas?->nama_kelas
                        ? 'Kelas: ' . $record->siswa->kelas->nama_kelas
                        : null),

                TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('predikat')
                    ->label('Predikat')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Sangat Baik'     => 'success',
                        'Baik'            => 'info',
                        'Berkembang'      => 'warning',
                        'Perlu Bimbingan' => 'danger',
                        default           => 'gray',
                    }),

                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(60)
                    ->tooltip(fn ($record) => $record->catatan)
                    ->toggleable(),

                TextColumn::make('guru.nama')
                    ->label('Dicatat oleh')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options(function () {
                        $user = \Illuminate\Support\Facades\Auth::user();
                        $query = Kelas::orderBy('tingkat')->orderBy('nama_kelas');
                        if ($user?->hasRole('Guru')) {
                            $guru = \App\Models\Guru::where('user_id', $user->id)->first();
                            if ($guru) {
                                $query->where('wali_kelas_id', $guru->id);
                            } else {
                                return [];
                            }
                        }
                        return $query->get()->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas}"]);
                    })
                    ->query(fn ($query, $data) => $data['value']
                        ? $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $data['value']))
                        : $query),

                SelectFilter::make('predikat')
                    ->label('Predikat')
                    ->options([
                        'Sangat Baik'     => 'Sangat Baik',
                        'Baik'            => 'Baik',
                        'Berkembang'      => 'Mulai Berkembang',
                        'Perlu Bimbingan' => 'Perlu Bimbingan',
                    ]),

                SelectFilter::make('guru_id')
                    ->label('Guru')
                    ->relationship('guru', 'nama')
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
