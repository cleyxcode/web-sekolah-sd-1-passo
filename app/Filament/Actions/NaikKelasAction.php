<?php

namespace App\Filament\Actions;

use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;

class NaikKelasAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'naik_kelas';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Proses Naik Kelas')
            ->icon('heroicon-o-academic-cap')
            ->color('success')
            ->requiresConfirmation(false)
            ->modalHeading('Proses Kenaikan Kelas Otomatis')
            ->modalDescription(null)
            ->modalWidth('lg')
            // ── AUTHORIZATION: hanya Super Admin & Kepala Sekolah ────────
            ->authorize(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->visible(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->form([
                Select::make('tahun_ajaran_baru_id')
                    ->label('Tahun Ajaran Baru (Tujuan)')
                    ->helperText('Siswa yang naik kelas akan dipindahkan ke tahun ajaran ini.')
                    ->options(TahunAjaran::orderBy('nama')->pluck('nama', 'id'))
                    ->searchable()
                    ->preload()
                    ->required(),
            ])
            ->modalSubmitActionLabel('Ya, Proses Naik Kelas')
            ->modalCancelActionLabel('Batal')
            ->action(function (array $data): void {
                $tahunAjaranBaruId = $data['tahun_ajaran_baru_id'];
                $tahunAjaranBaru   = TahunAjaran::findOrFail($tahunAjaranBaruId);

                // Ambil tahun ajaran yang aktif saat ini (sumber siswa)
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                if (!$tahunAjaranAktif) {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Tidak ada tahun ajaran yang sedang aktif. Aktifkan tahun ajaran terlebih dahulu.')
                        ->danger()
                        ->send();
                    return;
                }

                if ($tahunAjaranAktif->id === $tahunAjaranBaruId) {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Tahun ajaran baru tidak boleh sama dengan tahun ajaran yang sedang aktif.')
                        ->danger()
                        ->send();
                    return;
                }

                DB::transaction(function () use ($tahunAjaranAktif, $tahunAjaranBaruId, $tahunAjaranBaru) {
                    // Ambil semua kelas di tahun ajaran aktif, urutkan tingkat DESC
                    // agar saat update kelas_id tidak tabrakan dengan mapping baru
                    $kelasAktif = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                        ->orderBy('tingkat', 'desc')
                        ->get();

                    $totalNaik   = 0;
                    $totalLulus  = 0;

                    foreach ($kelasAktif as $kelas) {
                        // Ambil siswa aktif di kelas ini
                        $siswas = Siswa::where('kelas_id', $kelas->id)
                            ->where('status', 'aktif')
                            ->get();

                        if ($siswas->isEmpty()) {
                            continue;
                        }

                        if ($kelas->tingkat >= 6) {
                            // ── KELAS 6 → LULUS / ALUMNI ─────────────────────────
                            foreach ($siswas as $siswa) {
                                // Simpan riwayat
                                RiwayatKelas::create([
                                    'siswa_id'       => $siswa->id,
                                    'kelas_id'       => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status'         => 'lulus',
                                ]);

                                // Update siswa → lulus, lepas dari kelas
                                $siswa->update([
                                    'status'          => 'lulus',
                                    'kelas_id'        => null,
                                    'tahun_ajaran_id' => $tahunAjaranBaruId,
                                ]);

                                $totalLulus++;
                            }
                        } else {
                            // ── KELAS 1–5 → NAIK KE TINGKAT BERIKUTNYA ──────────
                            $tingkatBaru = $kelas->tingkat + 1;

                            // Cari kelas tujuan di tahun ajaran baru dengan tingkat+1
                            // Cocokkan nama_kelas jika ada, atau ambil kelas pertama dengan tingkat yang sama
                            $kelasTujuan = Kelas::where('tahun_ajaran_id', $tahunAjaranBaruId)
                                ->where('tingkat', $tingkatBaru)
                                ->where('nama_kelas', $kelas->nama_kelas) // coba cocokkan nama (misal: 1A → 2A)
                                ->first();

                            // Fallback: ambil kelas pertama dengan tingkat baru
                            if (!$kelasTujuan) {
                                $kelasTujuan = Kelas::where('tahun_ajaran_id', $tahunAjaranBaruId)
                                    ->where('tingkat', $tingkatBaru)
                                    ->first();
                            }

                            foreach ($siswas as $siswa) {
                                // Simpan riwayat dengan status 'naik'
                                RiwayatKelas::create([
                                    'siswa_id'        => $siswa->id,
                                    'kelas_id'        => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status'          => 'naik',
                                ]);

                                // Update siswa → kelas baru (atau null jika belum ada kelas tujuan)
                                $siswa->update([
                                    'kelas_id'        => $kelasTujuan?->id,
                                    'tahun_ajaran_id' => $tahunAjaranBaruId,
                                ]);

                                $totalNaik++;
                            }
                        }
                    }

                    // Tampilkan notifikasi sukses
                    $message = "Proses selesai: {$totalNaik} siswa naik kelas, {$totalLulus} siswa lulus/alumni.";

                    if ($totalNaik === 0 && $totalLulus === 0) {
                        Notification::make()
                            ->title('Tidak Ada Perubahan')
                            ->body('Tidak ada siswa aktif yang ditemukan di tahun ajaran yang sedang aktif.')
                            ->warning()
                            ->send();
                        return;
                    }

                    Notification::make()
                        ->title('Kenaikan Kelas Berhasil! 🎓')
                        ->body($message)
                        ->success()
                        ->duration(8000)
                        ->send();
                });
            });
    }
}
