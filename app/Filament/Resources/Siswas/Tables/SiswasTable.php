<?php

namespace App\Filament\Resources\Siswas\Tables;

use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

class SiswasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),
                TextColumn::make('nama')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('primary')
                    ->placeholder('—')
                    ->sortable(),
                TextColumn::make('kelas.tingkat')
                    ->label('Tingkat')
                    ->formatStateUsing(fn ($state) => $state ? "Kelas {$state}" : '—')
                    ->badge()
                    ->color('gray')
                    ->sortable(),
                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif'  => 'success',
                        'lulus'  => 'info',
                        'pindah' => 'warning',
                        default  => 'gray',
                    }),
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

                // ── ACTION NAIK KELAS PER-SISWA ──────────────────────────────
                Action::make('naik_kelas_siswa')
                    ->label('Naik Kelas')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn ($record) => "Naikkan Kelas: {$record->nama}")
                    ->modalDescription(fn ($record): string => self::buildModalDescription($record))
                    ->modalSubmitActionLabel('Ya, Naikkan Sekarang')
                    ->visible(fn ($record): bool =>
                        $record->status === 'aktif'
                        && $record->kelas_id !== null
                        && (auth()->user()?->can('naik-kelas') ?? false)
                    )
                    ->authorize(fn ($record): bool => auth()->user()?->can('naik-kelas') ?? false)
                    ->action(function ($record): void {
                        $kelas = $record->kelas;

                        if (!$kelas) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Siswa belum memiliki kelas.')
                                ->danger()
                                ->send();
                            return;
                        }

                        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                        if (!$tahunAjaranAktif) {
                            Notification::make()
                                ->title('Gagal')
                                ->body('Tidak ada tahun ajaran aktif.')
                                ->danger()
                                ->send();
                            return;
                        }

                        DB::transaction(function () use ($record, $kelas, $tahunAjaranAktif) {
                            if ($kelas->tingkat >= 6) {
                                // LULUS
                                RiwayatKelas::create([
                                    'siswa_id'        => $record->id,
                                    'kelas_id'        => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status'          => 'lulus',
                                ]);

                                $record->update([
                                    'status'   => 'lulus',
                                    'kelas_id' => null,
                                ]);

                                Notification::make()
                                    ->title('Siswa Lulus! 🎓')
                                    ->body("{$record->nama} telah dinyatakan lulus dan menjadi alumni.")
                                    ->success()
                                    ->send();
                            } else {
                                // NAIK KELAS
                                $tingkatBaru = $kelas->tingkat + 1;
                                $kelasBaru   = Kelas::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                                    ->where('tingkat', $tingkatBaru)
                                    ->where('nama_kelas', $kelas->nama_kelas)
                                    ->first()
                                    ?? Kelas::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                                        ->where('tingkat', $tingkatBaru)
                                        ->first();

                                RiwayatKelas::create([
                                    'siswa_id'        => $record->id,
                                    'kelas_id'        => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status'          => 'naik',
                                ]);

                                $record->update([
                                    'kelas_id' => $kelasBaru?->id,
                                ]);

                                $kelasLabel = $kelasBaru
                                    ? "Kelas {$kelasBaru->tingkat} - {$kelasBaru->nama_kelas}"
                                    : "Tingkat {$tingkatBaru} (kelas belum tersedia)";

                                Notification::make()
                                    ->title('Naik Kelas Berhasil! ⬆️')
                                    ->body("{$record->nama} telah dipindahkan ke {$kelasLabel}.")
                                    ->success()
                                    ->send();
                            }
                        });
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Buat deskripsi modal konfirmasi naik kelas per-siswa.
     */
    private static function buildModalDescription($record): string
    {
        $kelas = $record->kelas;
        if (!$kelas) {
            return 'Siswa ini belum memiliki kelas.';
        }

        if ($kelas->tingkat >= 6) {
            return "Siswa ini berada di Kelas 6 ({$kelas->nama_kelas}). Jika dilanjutkan, siswa akan dinyatakan LULUS dan menjadi alumni.";
        }

        $tingkatBaru = $kelas->tingkat + 1;
        return "Siswa ini berada di Kelas {$kelas->tingkat} ({$kelas->nama_kelas}). Jika dilanjutkan, siswa akan naik ke Kelas {$tingkatBaru}.";
    }
}
