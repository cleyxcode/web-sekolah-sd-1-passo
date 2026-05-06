<?php

// Alamat folder tempat file ini berada
namespace App\Filament\Resources\Tugas\Tables;

// Mengimpor elemen penyusun tabel (Kolom teks, tombol, penyaring data, dll)
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Carbon;

/**
 * TugasTable
 * 
 * Kelas ini mengatur susunan dan tampilan tabel pada halaman "Daftar Tugas".
 */
class TugasTable
{
    public static function configure(Table $table): Table
    {
        return $table
            // ->columns() mengatur kolom apa saja yang terlihat
            ->columns([
                
                // Menampilkan kolom Judul Tugas
                TextColumn::make('judul')
                    ->label('Judul Tugas')
                    ->searchable() // Bisa dicari via kolom pencarian
                    ->sortable()   // Bisa diklik untuk urut (A-Z)
                    ->weight('bold') // Judul ditebalkan agar menonjol
                    // ->description() menambah tulisan kecil redup di bawah teks utama
                    ->description(fn($record) => $record->mata_pelajaran ?? '—'),

                // Menampilkan nama kelas (dengan memanggil relasi 'kelas', lalu ambil kolom 'nama_kelas')
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()        // Tampilan lencana keren
                    ->color('info')  // Warna lencana biru muda
                    ->sortable(),

                // Menampilkan nama guru pembuat tugas
                TextColumn::make('guru.nama')
                    ->label('Guru Pemberi')
                    ->searchable()
                    ->sortable(),

                // Menampilkan tanggal & jam batas akhir pengumpulan tugas
                TextColumn::make('deadline')
                    ->label('Deadline')
                    ->dateTime('d M Y, H:i') // Format: "01 Jan 2026, 12:00"
                    ->sortable()
                    // Mengubah warnanya: JIKA sudah lewat batas waktu (isPast), beri warna MERAH (danger), jika belum HIJAU (success)
                    ->color(fn($record) => $record->deadline->isPast() ? 'danger' : 'success')
                    // Memunculkan sisa waktu ("2 hari lagi", "Sudah lewat") sebagai tulisan kecil di bawah jam
                    ->description(fn($record) => $record->sisa_waktu),

                // Menampilkan status aktif/selesai
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    // Menyesuaikan warna lencana sesuai kata-kata di database
                    ->color(fn(string $state): string => match($state) {
                        'aktif'      => 'success', // Hijau
                        'selesai'    => 'info',    // Biru muda
                        'dibatalkan' => 'danger',  // Merah
                        default      => 'gray',    // Abu-abu
                    })
                    // Mengubah bentuk tulisan (huruf depannya kapital)
                    ->formatStateUsing(fn(string $state): string => match($state) {
                        'aktif'      => 'Aktif',
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                        default      => $state,
                    }),

                // Menampilkan tanggal data ini dimasukkan ke sistem
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime('d M Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true), // Sembunyikan secara bawaan
            ])
            // ->filters() digunakan untuk menyaring isi tabel (Pojok Kanan Atas)
            ->filters([
                
                // Menyaring daftar tabel hanya untuk 1 kelas tertentu
                SelectFilter::make('kelas_id')
                    ->label('Filter Kelas')
                    ->relationship('kelas', 'nama_kelas'), // Munculkan pilihan nama kelas otomatis dari database

                // Menyaring daftar tabel hanya untuk status tertentu (misal: aktif saja)
                SelectFilter::make('status')
                    ->label('Filter Status')
                    ->options([
                        'aktif'      => 'Aktif',
                        'selesai'    => 'Selesai',
                        'dibatalkan' => 'Dibatalkan',
                    ]),
            ])
            // ->recordActions() adalah tombol aksi di setiap baris kanan tabel
            ->recordActions([
                EditAction::make(),   // Tombol Edit (Pensil)
                DeleteAction::make(), // Tombol Hapus (Tong Sampah)
            ])
            // ->defaultSort() mengatur bagaimana baris tabel ini diurutkan pertama kali halaman dibuka
            // Secara bawaan urutkan berdasarkan tugas yang paling MENDESAK (deadline-nya paling dekat)
            ->defaultSort('deadline', 'asc')
            // Pesan kosong jika belum ada data tugas sama sekali di database
            ->emptyStateHeading('Belum ada tugas')
            ->emptyStateDescription('Klik tombol "Buat Tugas" untuk menambahkan tugas baru.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list'); // Gambar ikon kosong
    }
}
