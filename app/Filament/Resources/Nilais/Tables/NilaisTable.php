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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Torgodly\Html2Media\Actions\Html2MediaAction;

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
                    ->options(function () {
                        $user = auth()->user();

                        if ($user->hasRole('Super Admin')) {
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

                // 📄 Cetak E-Rapor per Siswa
                Html2MediaAction::make('cetak_rapor')
                    ->label('E-Rapor')
                    ->icon('heroicon-o-printer')
                    ->color('success')
                    ->print()
                    ->preview()
                    ->savePdf()
                    ->filename(fn ($record) => 'E-Rapor_' . str($record->siswa?->nama ?? 'siswa')->slug() . '_' . $record->semester . '_' . $record->jenis_ujian)
                    ->orientation('portrait')
                    ->format('a4', 'mm')
                    ->content(fn ($record) => self::buildRaporContent($record)),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    /**
     * Build konten rapor per siswa untuk satu record nilai.
     * Mengambil semua nilai siswa tersebut di semester & jenis ujian yang sama.
     */
    private static function buildRaporContent($record): \Illuminate\Contracts\View\View
    {
        $siswa      = $record->siswa;
        $kelas      = $record->kelas;
        $semester   = $record->semester;
        $jenisUjian = $record->jenis_ujian;
        $tahunAjaran = $record->tahunAjaran;

        // Ambil semua nilai siswa untuk semester & jenis ujian ini
        $nilais = Nilai::with('mataPelajaran')
            ->where('siswa_id', $siswa->id)
            ->where('kelas_id', $kelas?->id)
            ->where('semester', $semester)
            ->where('jenis_ujian', $jenisUjian)
            ->where('tahun_ajaran_id', $tahunAjaran?->id)
            ->get();

        // Load relasi wali kelas
        $kelas?->load('waliKelas');

        $sekolah = \App\Models\SettingSekolah::first();

        return view('rapor.cetak-rapor', compact(
            'siswa', 'kelas', 'semester', 'jenisUjian', 'tahunAjaran', 'nilais', 'sekolah'
        ));
    }
}
