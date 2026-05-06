<?php

// Lokasi folder
namespace App\Filament\Resources\Presensis\Tables;

// Model
use App\Models\Kelas;
// Tombol dan Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
// Penyaring data (Filter)
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Indicator;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DatePicker;
use Illuminate\Support\Carbon;

/**
 * PresensisTable
 * 
 * Mengatur kolom dan tombol pencarian untuk data tabel absen siswa.
 * File ini cukup panjang karena memiliki sangat banyak fitur Filter/Penyaring khusus.
 */
class PresensisTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // Kolom Nama Siswa
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    // ->description memunculkan tulisan nama kelas kecil di bawah nama siswa
                    ->description(fn ($record) => $record->kelas?->nama_kelas
                        ? 'Kelas: ' . $record->kelas->nama_kelas
                        : null),

                // Kolom Kelas
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge() // Bentuk lencana
                    ->color('info') // Warna biru
                    ->sortable(),

                // Kolom Tanggal Absen
                TextColumn::make('tanggal')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                // Kolom Status (Hadir, Sakit, dsb)
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'hadir'  => 'success', // Hijau
                        'sakit'  => 'warning', // Kuning/Oranye
                        'izin'   => 'info',    // Biru muda
                        'alpha'  => 'danger',  // Merah
                        default  => 'gray',
                    }),

                // Kolom Keterangan
                TextColumn::make('keterangan')
                    ->label('Keterangan')
                    ->limit(40) // Dipotong maksimal 40 karakter agar rapi
                    ->placeholder('-') // Tampilkan '-' jika kosong
                    ->toggleable(), // Bisa disembunyikan lewat menu kolom

                // Kolom Foto Absen
                ImageColumn::make('foto_absen')
                    ->label('Foto Absen')
                    ->height(50)
                    ->width(70)
                    ->placeholder('Tidak ada foto')
                    ->circular(false), // Gambar kotak biasa (bukan lingkaran)

                // Kolom Guru Pencatat
                TextColumn::make('guru.nama')
                    ->label('Guru Pencatat')
                    ->searchable()
                    ->toggleable(),

                // Kolom Tahun Ajaran (Disembunyikan bawaan)
                TextColumn::make('tahunAjaran.nama')
                    ->label('Tahun Ajaran')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            /**
             * DAFTAR FITUR FILTER
             * Ini sangat penting karena absen siswa sangat banyak jumlah datanya per hari.
             */
            ->filters([
                
                // 1. FILTER BERDASARKAN KELAS
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->options(function () {
                        // Secara otomatis menyesuaikan jika yang login Guru, maka cuma muncul kelas perwaliannya
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

                // 2. FILTER BERDASARKAN STATUS
                SelectFilter::make('status')
                    ->label('Status Kehadiran')
                    ->options([
                        'hadir' => 'Hadir',
                        'sakit' => 'Sakit',
                        'izin'  => 'Izin',
                        'alpha' => 'Alpha',
                    ]),

                // 3. FILTER UNTUK SATU TANGGAL TERTENTU (Contoh: "Hanya tampilkan absen hari ini")
                Filter::make('tanggal_hari')
                    ->label('Filter Hari')
                    ->form([
                        DatePicker::make('tanggal')
                            ->label('Pilih Tanggal'),
                    ])
                    // Query (Pencarian SQL) untuk memproses filter ini
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['tanggal'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', $date),
                            );
                    })
                    // Menampilkan informasi filter (Indicator) yang sedang aktif di pojok atas tabel
                    ->indicateUsing(function (array $data): ?string {
                        if (!$data['tanggal']) {
                            return null;
                        }
                        return 'Tanggal: ' . Carbon::parse($data['tanggal'])->format('d M Y');
                    }),

                // 4. FILTER RENTANG MINGGUAN / TANGGAL DARI-SAMPAI
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
                            // Cari absen dengan tanggal LEBIH DARI atau SAMA DENGAN tanggal mulai
                            ->when(
                                $data['dari'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '>=', $date),
                            )
                            // Cari absen dengan tanggal KURANG DARI atau SAMA DENGAN tanggal selesai
                            ->when(
                                $data['sampai'],
                                fn (Builder $query, $date): Builder => $query->whereDate('tanggal', '<=', $date),
                            );
                    })
                    ->indicateUsing(function (array $data): array {
                        // Tampilkan indikator di atas tabel jika filter ini dipakai
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

                // 5. FILTER BERDASARKAN BULAN (Januari - Desember)
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
                        // Menyaring database tepat sesuai angka bulan (whereMonth)
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereMonth('tanggal', $value)
                        );
                    }),

                // 6. FILTER BERDASARKAN SEMESTER
                SelectFilter::make('semester')
                    ->label('Filter Semester')
                    ->options([
                        '1' => 'Semester 1',
                        '2' => 'Semester 2',
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        // Karena kolom 'semester' tidak ada di tabel presensi, 
                        // kita cari tabel 'TahunAjaran' yang terhubung dengan absen ini, lalu cek semesternya
                        return $query->when(
                            $data['value'],
                            fn (Builder $query, $value): Builder => $query->whereHas('tahunAjaran', fn ($q) => $q->where('semester', $value))
                        );
                    }),

                // 7. FILTER HANYA ABSEN YANG MENGANDUNG FOTO BUKTI
                Filter::make('ada_foto')
                    ->label('Hanya yang Ada Foto')
                    ->query(fn (Builder $query): Builder => $query->whereNotNull('foto_absen')),

                // 8. FILTER TAHUN AJARAN
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
            // Urutkan tabel: Absen yang paling BARU hari ini berada di paling ATAS
            ->defaultSort('tanggal', 'desc');
    }
}
