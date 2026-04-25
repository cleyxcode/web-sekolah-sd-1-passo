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
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Filters\Indicator;
use Illuminate\Support\Carbon;

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

                Filter::make('tanggal_hari')
                    ->label('Filter Hari')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Pilih Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['tanggal']) {
                            return null;
                        }
                        return 'Tanggal: ' . Carbon::parse($data['tanggal'])->format('d M Y');
                    }),

                Filter::make('mingguan')
                    ->label('Filter Mingguan (Rentang)')
                    ->form([
                        DatePicker::make('dari')
                            ->label('Dari Tanggal'),
                        DatePicker::make('sampai')
                            ->label('Sampai Tanggal'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            ->when(
                                $data['sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        $indicators = [];
                        if ($data['dari'] ?? null) {
                            $indicators[] = Indicator::make('Dari ' . Carbon::parse($data['dari'])->format('d M Y'))
                                ->removeField('dari');
                        }
                        if ($data['sampai'] ?? null) {
                            $indicators[] = Indicator::make('Sampai ' . Carbon::parse($data['sampai'])->format('d M Y'))
                                ->removeField('sampai');
                        }
                        return $indicators;
                    }),

                SelectFilter::make('bulan')
                    ->label('Filter Bulanan')
                    ->options([
                        '01' => 'Januari',
                        '02' => 'Februari',
                        '03' => 'Maret',
                        '04' => 'April',
                        '05' => 'Mei',
                        '06' => 'Juni',
                        '07' => 'Juli',
                        '08' => 'Agustus',
                        '09' => 'September',
                        '10' => 'Oktober',
                        '11' => 'November',
                        '12' => 'Desember',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereMonth('tanggal', $value)
                        );
                    }),

                SelectFilter::make('semester')
                    ->label('Filter Semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('tahunAjaran', fn ($q) => $q->where('semester', $value))
                        );
                    }),

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
