<?php

// Alamat folder

namespace App\Filament\Resources\Siswas\Tables;

// Impor model
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\TahunAjaran;
// Impor tombol aksi
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
// Impor kolom tabel
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\DB;

/**
 * SiswasTable
 *
 * Mengatur kolom tabel dan tombol khusus "Naik Kelas" pada data Siswa.
 */
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

                // Menampilkan nama kelas (contoh: 1A, 2B)
                TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas')
                    ->badge()
                    ->color('primary') // Warna biru
                    ->placeholder('—') // Tampilkan garis jika belum ada kelas
                    ->sortable(),

                // Menampilkan tingkat kelas (contoh: Kelas 1)
                TextColumn::make('kelas.tingkat')
                    ->label('Tingkat')
                    ->formatStateUsing(fn ($state) => $state ? "Kelas {$state}" : '—')
                    ->badge()
                    ->color('gray')
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('L/P')
                    ->badge(),

                // Menyesuaikan warna lencana berdasarkan status
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'aktif' => 'success', // Aktif = Hijau
                        'lulus' => 'info',    // Lulus = Biru muda
                        'pindah' => 'warning', // Pindah = Kuning/Oranye
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                // Kosong untuk saat ini
            ])
            ->recordActions([
                ViewAction::make(), // Tombol lihat detail (Mata)
                EditAction::make(), // Tombol edit (Pensil)

                // ── TOMBOL KHUSUS: NAIK KELAS PER-SISWA ──────────────────────────────
                // Tombol ini muncul di sebelah tombol Edit untuk setiap baris siswa
                Action::make('naik_kelas_siswa')
                    ->label('Naik Kelas')
                    ->icon('heroicon-o-arrow-up-circle')
                    ->color('success')
                    ->requiresConfirmation() // Munculkan peringatan "Apakah Anda yakin?"
                    ->modalHeading(fn ($record) => "Naikkan Kelas: {$record->nama}")
                    // Isi tulisan pada pop-up peringatan memanggil fungsi buildModalDescription di bawah
                    ->modalDescription(fn ($record): string => self::buildModalDescription($record))
                    ->modalSubmitActionLabel('Ya, Naikkan Sekarang')
                    // Tombol ini HANYA MUNCUL jika siswa aktif, punya kelas, dan yg login punya hak 'naik-kelas'
                    ->visible(fn ($record): bool => $record->status === 'aktif'
                        && $record->kelas_id !== null
                        && (auth()->user()?->can('naik-kelas') ?? false)
                    )
                    // Pengecekan keamanan ganda
                    ->authorize(fn ($record): bool => auth()->user()?->can('naik-kelas') ?? false)

                    // PROSES YANG TERJADI SAAT TOMBOL DIKLIK:
                    ->action(function ($record): void {
                        $kelas = $record->kelas;

                        // 1. Cek dulu apakah siswa ini punya kelas?
                        if (! $kelas) {
                            Notification::make()->title('Gagal')->body('Siswa belum memiliki kelas.')->danger()->send();

                            return;
                        }

                        // 2. Cek apakah ada tahun ajaran yang sedang aktif?
                        $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
                        if (! $tahunAjaranAktif) {
                            Notification::make()->title('Gagal')->body('Tidak ada tahun ajaran aktif.')->danger()->send();

                            return;
                        }

                        // 3. Lakukan proses database secara aman (Transaction)
                        // Jika ada satu proses yang gagal, batalkan semuanya (mencegah data setengah jadi)
                        DB::transaction(function () use ($record, $kelas, $tahunAjaranAktif) {

                            // JIKA SISWA SUDAH KELAS 6 -> PROSES KELULUSAN
                            if ($kelas->tingkat >= 6) {
                                // Catat di riwayat bahwa dia lulus
                                RiwayatKelas::create([
                                    'siswa_id' => $record->id,
                                    'kelas_id' => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status' => 'lulus',
                                ]);

                                // Update data siswa: status jadi 'lulus', cabut dari kelas lamanya
                                $record->update([
                                    'status' => 'lulus',
                                    'kelas_id' => null,
                                ]);

                                // Munculkan pop-up hijau di layar pojok
                                Notification::make()
                                    ->title('Siswa Lulus! 🎓')
                                    ->body("{$record->nama} telah dinyatakan lulus dan menjadi alumni.")
                                    ->success()
                                    ->send();
                            }
                            // JIKA MASIH KELAS 1-5 -> PROSES NAIK KELAS
                            else {
                                $tingkatBaru = $kelas->tingkat + 1; // Naik 1 tingkat (contoh: dari 1 jadi 2)

                                // Coba cari kelas baru yang namanya sama (contoh: dari "1A" otomatis dicari "2A")
                                $kelasBaru = Kelas::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                                    ->where('tingkat', $tingkatBaru)
                                    ->where('nama_kelas', $kelas->nama_kelas)
                                    ->first()
                                    // Kalau tidak ketemu "2A", sembarang saja ambil kelas "2" yang ada
                                    ?? Kelas::where('tahun_ajaran_id', $kelas->tahun_ajaran_id)
                                        ->where('tingkat', $tingkatBaru)
                                        ->first();

                                // Catat riwayat lama
                                RiwayatKelas::create([
                                    'siswa_id' => $record->id,
                                    'kelas_id' => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status' => 'naik',
                                ]);

                                // Masukkan siswa ke kelas barunya (bisa null jika belum ada kelas tingkat atasnya di DB)
                                $record->update([
                                    'kelas_id' => $kelasBaru?->id,
                                ]);

                                // Munculkan notifikasi ke layar
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
     * Fungsi kecil untuk membuat pesan penjelasan pada pop-up konfirmasi.
     * Jika kelas 6 -> tulisannya beda (akan lulus).
     */
    private static function buildModalDescription($record): string
    {
        $kelas = $record->kelas;
        if (! $kelas) {
            return 'Siswa ini belum memiliki kelas.';
        }

        if ($kelas->tingkat >= 6) {
            return "Siswa ini berada di Kelas 6 ({$kelas->nama_kelas}). Jika dilanjutkan, siswa akan dinyatakan LULUS dan menjadi alumni.";
        }

        $tingkatBaru = $kelas->tingkat + 1;

        return "Siswa ini berada di Kelas {$kelas->tingkat} ({$kelas->nama_kelas}). Jika dilanjutkan, siswa akan naik ke Kelas {$tingkatBaru}.";
    }
}
