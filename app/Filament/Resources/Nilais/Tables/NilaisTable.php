<?php

namespace App\Filament\Resources\Nilais\Tables;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

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
                    ->label('Wali Kelas')
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
                        $user = auth()->user();

                        if ($user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah')) {
                            return Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                                ->get()
                                ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas}"]);
                        }

                        $guru = Guru::where('user_id', $user->id)->first();
                        if ($guru) {
                            return Kelas::where('wali_kelas_id', $guru->id)
                                ->orderBy('tingkat')->orderBy('nama_kelas')
                                ->get()
                                ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas}"]);
                        }

                        return collect();
                    })
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

                // 📄 Cetak E-Rapor per Siswa — buka halaman cetak di tab baru
                Action::make('cetak_rapor')
                    ->label('E-Rapor')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->url(fn ($record) => route('admin.cetak-rapor', $record->id))
                    ->openUrlInNewTab()
                    ->tooltip('Buka halaman cetak E-Rapor siswa ini'),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->modifyQueryUsing(fn ($query) => $query
                ->leftJoin('kelas as k_sort', 'nilais.kelas_id', '=', 'k_sort.id')
                ->leftJoin('siswas as s_sort', 'nilais.siswa_id', '=', 's_sort.id')
                ->orderBy('k_sort.tingkat', 'asc')
                ->orderBy('k_sort.nama_kelas', 'asc')
                ->orderBy('s_sort.nama', 'asc')
                ->select('nilais.*')
            );
    }
}
