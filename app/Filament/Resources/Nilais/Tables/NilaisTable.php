<?php

// Lokasi folder
namespace App\Filament\Resources\Nilais\Tables;

// Model
use App\Models\Guru;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Nilai;
use App\Models\Siswa;
use App\Models\TahunAjaran;
// Tombol dan Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * NilaisTable
 * 
 * Mengatur kolom yang ditampilkan pada halaman daftar Nilai Siswa.
 */
class NilaisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                // Nama Siswa beserta tulisan kelas kecil di bawahnya
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->kelas?->nama_kelas
                        ? 'Kelas: ' . $record->kelas->nama_kelas
                        : null),

                // Nama Kelas
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge() // Berbentuk lencana
                    ->color('info') // Warna biru
                    ->sortable(),

                // Nama Mata Pelajaran
                TextColumn::make('mataPelajaran.nama')
                    ->label('Mata Pelajaran')
                    ->searchable()
                    ->sortable(),

                // Semester (1 atau 2)
                TextColumn::make('semester')
                    ->label('Semester')
                    ->badge()
                    ->color('gray'),

                // Jenis Ujian (UTS / UAS)
                TextColumn::make('jenis_ujian')
                    ->label('Jenis Ujian')
                    ->badge()
                    // Warna kuning (warning) untuk UTS, hijau (success) untuk UAS
                    ->color(fn (string $state): string => match ($state) {
                        'UTS' => 'warning',
                        'UAS' => 'success',
                        default => 'gray',
                    }),

                // Angka Nilai (0 - 100)
                TextColumn::make('nilai_angka')
                    ->label('Nilai')
                    ->numeric(decimalPlaces: 1) // Tampilkan 1 angka di belakang koma (contoh: 80.0)
                    ->sortable()
                    ->badge() // Berbentuk lencana
                    // Warnanya berubah otomatis: 
                    // Lebih dari 85 = Hijau (Bagus)
                    // Lebih dari 70 = Kuning (Cukup)
                    // Kurang dari 70 = Merah (Kurang/Gagal)
                    ->color(fn ($state): string => match (true) {
                        $state >= 85 => 'success',
                        $state >= 70 => 'warning',
                        default      => 'danger',
                    }),

                // Nama Guru Penginput
                TextColumn::make('guru.nama')
                    ->label('Wali Kelas')
                    ->searchable()
                    ->toggleable(), // Bisa disembunyikan lewat menu kolom agar tabel tidak kepanjangan

                // Tahun Ajaran
                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyi secara bawaan

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            
            // --- DAFTAR FITUR FILTER / PENYARING ---
            ->filters([
                
                // 1. Filter Berdasarkan Kelas (Hanya munculkan kelas yang diajar)
                SelectFilter::make('kelas_id')
                    // Memastikan kita memfilter berdasarkan kolom 'kelas_id' pada tabel 'nilais'
                    ->attribute('nilais.kelas_id') 
                    ->label('Filter Kelas')
                    ->options(function () {
                        $user = auth()->user();

                        // Super Admin: Semua kelas
                        if ($user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah')) {
                            return Kelas::orderBy('tingkat')->orderBy('nama_kelas')
                                ->get()
                                ->mapWithKeys(fn ($k) => [$k->id => "Kelas {$k->nama_kelas}"]);
                        }

                        // Guru Wali: Hanya kelas dia sendiri
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

                // 2. Filter Berdasarkan Semester
                SelectFilter::make('semester')
                    ->label('Semester')
                    ->options(['1' => 'Semester 1', '2' => 'Semester 2']),

                // 3. Filter Jenis Ujian
                SelectFilter::make('jenis_ujian')
                    ->label('Jenis Ujian')
                    ->options(['UTS' => 'UTS', 'UAS' => 'UAS']),

                // 4. Filter Mata Pelajaran
                SelectFilter::make('mata_pelajaran_id')
                    ->label('Mata Pelajaran')
                    ->relationship('mataPelajaran', 'nama')
                    ->searchable()
                    ->preload(),
            ])
            
            // --- DAFTAR TOMBOL AKSI DI KANAN ---
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),

                // TOMBOL SPESIAL: Cetak E-Rapor per Siswa
                // Ini akan memanggil controller khusus untuk membuka halaman rapor dalam bentuk PDF/Print
                Action::make('cetak_rapor')
                    ->label('E-Rapor')
                    ->icon('heroicon-o-printer') // Ikon printer
                    ->color('success')           // Warna hijau
                    // Mengarahkan ke rute 'admin.cetak-rapor' dengan membawa ID siswa tersebut
                    ->url(fn ($record) => route('admin.cetak-rapor', $record->id))
                    ->openUrlInNewTab() // Buka tab baru agar dashboard tidak tertutup
                    ->tooltip('Buka halaman cetak E-Rapor siswa ini'),
            ])
            
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])

            // --- CARA MENGURUTKAN TABEL DI AWAL ---
            // Secara bawaan, tabel akan diurutkan sangat rapi:
            // Kelas 1 di atas, lalu Kelas 2, lalu urut A-Z nama kelas, lalu A-Z nama siswanya.
            ->modifyQueryUsing(fn ($query) => $query
                ->leftJoin('kelas as k_sort', 'nilais.kelas_id', '=', 'k_sort.id')
                ->leftJoin('siswas as s_sort', 'nilais.siswa_id', '=', 's_sort.id')
                ->orderBy('k_sort.tingkat', 'asc')
                ->orderBy('k_sort.nama_kelas', 'asc')
                ->orderBy('s_sort.nama', 'asc')
                ->select('nilais.*') // Pastikan hanya data dari tabel nilais yang diambil
            );
    }
}
