<?php

namespace App\Filament\Resources\Presensis\Tables;

use App\Models\Kelas;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PresensisTable
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

                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir'  => 'success',
                        'sakit'  => 'warning',
                        'izin'   => 'info',
                        'alpha'  => 'danger',
                        default  => 'gray',
                    }),

                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40)
                    ->placeholder('-')
                    ->toggleable(),

                ImageColumn::make('foto_absen')
                    ->label('Foto Absen')
                    ->height(50)
                    ->width(70)
                    ->placeholder('Tidak ada foto')
                    ->circular(false),

                TextColumn::make('guru.nama')
                    ->label('Guru Pencatat')
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
                    ->searchable(),

                SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'sakit' => 'Sakit',
                        'izin'  => 'Izin',
                        'alpha' => 'Alpha',
                    ]),

                Filter::make('ada_foto')
                    ->label('Hanya yang Ada Foto')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('foto_absen')),

                SelectFilter::make('tahun_ajaran_id')
                    ->label('Tahun Ajaran')
                    ->relationship('tahunAjaran', 'nama'),
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
            ->defaultSort('tanggal', 'desc');
    }
}
