<?php

// Lokasi folder
namespace App\Filament\Resources\CatatanPerkembangans\Tables;

// Model
use App\Models\Kelas;
// Tombol dan Kolom
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

/**
 * CatatanPerkembangansTable
 * 
 * Mengatur tampilan kolom di halaman daftar catatan.
 */
class CatatanPerkembangansTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                
                // Nama Siswa dan tulisan kelas kecil di bawahnya
                TextColumn::make('siswa.nama')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->description(fn ($record) => $record->siswa?->kelas?->nama_kelas
                        ? 'Kelas: ' . $record->siswa->kelas->nama_kelas
                        : null),

                // Kelas
                TextColumn::make('siswa.kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                // Predikat
                TextColumn::make('predikat')
                    ->label('Predikat')
                    ->badge() // Berbentuk lencana
                    // Ganti warna otomatis berdasarkan isi teks
                    ->color(fn (string $state): string => match ($state) {
                        'Sangat Baik'     => 'success', // Hijau
                        'Baik'            => 'info',    // Biru muda
                        'Berkembang'      => 'warning', // Kuning
                        'Perlu Bimbingan' => 'danger',  // Merah
                        default           => 'gray',
                    }),

                // Isi tulisan catatan
                TextColumn::make('catatan')
                    ->label('Catatan')
                    ->limit(60) // Jangan tampilkan semua teks, potong maksimal 60 huruf agar tabel tidak terlalu besar
                    ->tooltip(fn ($record) => $record->catatan) // Kalau mouse diarahkan ke teks, tampilkan seluruh isinya (popup tooltip)
                    ->toggleable(),

                // Guru Pencatat
                TextColumn::make('guru.nama')
                    ->label('Dicatat oleh')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('created_at')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
            ])
            
            // --- DAFTAR FILTER ---
            ->filters([
                
                // Filter Berdasarkan Kelas
                SelectFilter::make('kelas')
                    ->label('Filter Kelas')
                    ->options(function () {
                        // Secara otomatis menyesuaikan jika guru login, cuma muncul opsi kelas yang dia walikan
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
                    // Fungsi pencarian SQL rumit: "Cari catatan milik siswa yang ID Kelasnya sama dengan yang dipilih di atas"
                    ->query(fn ($query, $data) => $data['value']
                        ? $query->whereHas('siswa', fn ($q) => $q->where('kelas_id', $data['value']))
                        : $query),

                // Filter Predikat Bintang
                SelectFilter::make('predikat')
                    ->label('Predikat')
                    ->options([
                        'Sangat Baik'     => 'Sangat Baik',
                        'Baik'            => 'Baik',
                        'Berkembang'      => 'Mulai Berkembang',
                        'Perlu Bimbingan' => 'Perlu Bimbingan',
                    ]),

                // Filter Nama Guru
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
            // Urutkan Catatan yang paling BARU muncul di baris paling atas
            ->defaultSort('created_at', 'desc');
    }
}
