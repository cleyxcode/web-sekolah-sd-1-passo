<?php

namespace App\Filament\Actions;

use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

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
            ->modalWidth('xl')
            ->authorize(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->visible(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->form(function (): array {
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                // Hitung jumlah siswa aktif saat ini
                $jumlahSiswa = Siswa::where('status', 'aktif')
                    ->whereNotNull('kelas_id')
                    ->count();

                // Hitung per tingkat
                $perTingkat = DB::table('siswas')
                    ->join('kelas', 'siswas.kelas_id', '=', 'kelas.id')
                    ->where('siswas.status', 'aktif')
                    ->select('kelas.tingkat', DB::raw('count(*) as total'))
                    ->groupBy('kelas.tingkat')
                    ->orderBy('kelas.tingkat')
                    ->get();

                $infoTingkat = $perTingkat->map(function ($item) {
                    $label = $item->tingkat >= 6
                        ? "Tingkat {$item->tingkat} (Kelas 6) → Lulus/Alumni: {$item->total} siswa"
                        : "Tingkat {$item->tingkat} → Tingkat " . ($item->tingkat + 1) . ": {$item->total} siswa";
                    return "• {$label}";
                })->implode("\n");

                // Cek apakah ada tahun ajaran baru (bukan yang aktif)
                $tahunAjaranOptions = TahunAjaran::orderByDesc('nama')
                    ->pluck('nama', 'id')
                    ->toArray();

                $infoBox = $tahunAjaranAktif
                    ? "**Tahun Ajaran Aktif:** {$tahunAjaranAktif->nama}\n\n**Siswa aktif:** {$jumlahSiswa} siswa\n\n{$infoBox_tingkat}"
                    : "⚠️ Tidak ada tahun ajaran aktif.";

                return [
                    Placeholder::make('info_proses')
                        ->label('')
                        ->content(function () use ($tahunAjaranAktif, $jumlahSiswa, $infoTingkat) {
                            if (!$tahunAjaranAktif) {
                                return new HtmlString(
                                    '<div style="padding:12px;background:#fef2f2;border:1px solid #fca5a5;border-radius:8px;color:#991b1b;">
                                        <strong>⚠️ Tidak ada tahun ajaran aktif!</strong><br>
                                        Aktifkan satu tahun ajaran terlebih dahulu sebelum melanjutkan.
                                    </div>'
                                );
                            }

                            $rows = nl2br(e($infoTingkat));
                            return new HtmlString("
                                <div style='padding:14px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;font-size:13px;'>
                                    <div style='font-weight:700;color:#15803d;margin-bottom:8px;'>📋 Ringkasan Proses Naik Kelas</div>
                                    <div style='color:#166534;margin-bottom:6px;'>Tahun Ajaran Aktif: <strong>{$tahunAjaranAktif->nama}</strong></div>
                                    <div style='color:#166534;margin-bottom:10px;'>Total Siswa Aktif: <strong>{$jumlahSiswa} siswa</strong></div>
                                    <div style='font-weight:600;color:#15803d;margin-bottom:4px;'>Distribusi Kenaikan:</div>
                                    <div style='color:#166534;font-family:monospace;font-size:12px;'>{$rows}</div>
                                </div>
                            ");
                        }),

                    Select::make('mode')
                        ->label('Mode Kenaikan Kelas')
                        ->options([
                            'same_tahun_ajaran' => '🔄 Naik kelas dalam tahun ajaran yang SAMA (pindah tingkat)',
                            'new_tahun_ajaran'  => '📅 Naik kelas ke tahun ajaran BARU',
                        ])
                        ->default('same_tahun_ajaran')
                        ->required()
                        ->native(false)
                        ->live()
                        ->helperText('Pilih "tahun ajaran yang sama" jika kelas tujuan sudah ada di sistem.'),

                    Select::make('tahun_ajaran_baru_id')
                        ->label('Tahun Ajaran Baru (Tujuan)')
                        ->helperText('Siswa yang naik kelas akan dipindahkan ke tahun ajaran ini.')
                        ->options(TahunAjaran::orderByDesc('nama')->pluck('nama', 'id'))
                        ->searchable()
                        ->preload()
                        ->required(fn ($get) => $get('mode') === 'new_tahun_ajaran')
                        ->visible(fn ($get) => $get('mode') === 'new_tahun_ajaran'),

                    Placeholder::make('warning_kelas')
                        ->label('')
                        ->content(new HtmlString(
                            '<div style="padding:10px 14px;background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;font-size:12px;color:#92400e;">
                                <strong>⚠️ Catatan Penting:</strong><br>
                                • Siswa kelas <strong>6 (tingkat 6)</strong> akan ditandai <strong>Lulus/Alumni</strong> dan dilepas dari kelas.<br>
                                • Pastikan kelas tujuan sudah dibuat terlebih dahulu agar siswa tidak kehilangan data kelas.<br>
                                • Proses ini tidak bisa dibatalkan secara otomatis — riwayat kelas akan tersimpan.
                            </div>'
                        )),

                    Checkbox::make('konfirmasi')
                        ->label('Saya memahami proses ini dan sudah memastikan data kelas tujuan sudah tersedia.')
                        ->required()
                        ->validationMessages(['required' => 'Centang konfirmasi untuk melanjutkan.']),
                ];
            })
            ->modalSubmitActionLabel('Ya, Proses Naik Kelas')
            ->modalCancelActionLabel('Batal')
            ->action(function (array $data): void {
                $mode             = $data['mode'] ?? 'same_tahun_ajaran';
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                if (!$tahunAjaranAktif) {
                    Notification::make()
                        ->title('Gagal')
                        ->body('Tidak ada tahun ajaran yang sedang aktif. Aktifkan tahun ajaran terlebih dahulu.')
                        ->danger()
                        ->send();
                    return;
                }

                // Tentukan ID tahun ajaran tujuan
                if ($mode === 'new_tahun_ajaran') {
                    $tahunAjaranTujuanId = $data['tahun_ajaran_baru_id'];

                    if ($tahunAjaranAktif->id === (int) $tahunAjaranTujuanId) {
                        Notification::make()
                            ->title('Gagal')
                            ->body('Tahun ajaran tujuan tidak boleh sama dengan tahun ajaran yang sedang aktif.')
                            ->danger()
                            ->send();
                        return;
                    }
                } else {
                    // Mode: same_tahun_ajaran → tetap pakai tahun ajaran yang aktif
                    $tahunAjaranTujuanId = $tahunAjaranAktif->id;
                }

                DB::transaction(function () use ($tahunAjaranAktif, $tahunAjaranTujuanId, $mode) {
                    // Ambil semua kelas di tahun ajaran aktif, urutkan tingkat DESC
                    $kelasAktif = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                        ->orderBy('tingkat', 'desc')
                        ->get();

                    $totalNaik   = 0;
                    $totalLulus  = 0;
                    $totalTidakAdaKelas = 0;

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
                                RiwayatKelas::create([
                                    'siswa_id'        => $siswa->id,
                                    'kelas_id'        => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status'          => 'lulus',
                                ]);

                                $siswa->update([
                                    'status'          => 'lulus',
                                    'kelas_id'        => null,
                                    'tahun_ajaran_id' => $tahunAjaranTujuanId,
                                ]);
                                $totalLulus++;
                            }
                        } else {
                            // ── KELAS 1–5 → NAIK KE TINGKAT BERIKUTNYA ──────────
                            $tingkatBaru = $kelas->tingkat + 1;

                            // Cari kelas tujuan dengan nama_kelas yang sama di tingkat baru
                            // Contoh: 1A → 2A, 1B → 2B, dst.
                            $kelasTujuan = Kelas::where('tahun_ajaran_id', $tahunAjaranTujuanId)
                                ->where('tingkat', $tingkatBaru)
                                ->where('nama_kelas', $tingkatBaru . substr($kelas->nama_kelas, 1))
                                ->first();

                            // Fallback 1: cocokkan suffix (A/B/C) saja
                            if (!$kelasTujuan) {
                                $suffix = preg_replace('/[0-9]/', '', $kelas->nama_kelas); // ambil huruf saja
                                $kelasTujuan = Kelas::where('tahun_ajaran_id', $tahunAjaranTujuanId)
                                    ->where('tingkat', $tingkatBaru)
                                    ->where('nama_kelas', 'like', '%' . $suffix)
                                    ->first();
                            }

                            // Fallback 2: ambil kelas pertama dengan tingkat baru
                            if (!$kelasTujuan) {
                                $kelasTujuan = Kelas::where('tahun_ajaran_id', $tahunAjaranTujuanId)
                                    ->where('tingkat', $tingkatBaru)
                                    ->first();
                            }

                            foreach ($siswas as $siswa) {
                                RiwayatKelas::create([
                                    'siswa_id'        => $siswa->id,
                                    'kelas_id'        => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status'          => 'naik',
                                ]);

                                if ($kelasTujuan) {
                                    $siswa->update([
                                        'kelas_id'        => $kelasTujuan->id,
                                        'tahun_ajaran_id' => $tahunAjaranTujuanId,
                                        'status'          => 'aktif',
                                    ]);
                                    $totalNaik++;
                                } else {
                                    // Kelas tujuan tidak ada — pastikan status tetap aktif
                                    // tapi catat tidak ada kelas tujuan
                                    $siswa->update([
                                        'kelas_id'        => null,
                                        'tahun_ajaran_id' => $tahunAjaranTujuanId,
                                        'status'          => 'aktif',
                                    ]);
                                    $totalTidakAdaKelas++;
                                }
                            }
                        }
                    }

                    // Hasil notifikasi
                    if ($totalNaik === 0 && $totalLulus === 0 && $totalTidakAdaKelas === 0) {
                        Notification::make()
                            ->title('Tidak Ada Perubahan')
                            ->body('Tidak ada siswa aktif yang ditemukan di kelas manapun.')
                            ->warning()
                            ->send();
                        return;
                    }

                    $body = "{$totalNaik} siswa berhasil naik kelas. {$totalLulus} siswa lulus/alumni.";

                    if ($totalTidakAdaKelas > 0) {
                        $body .= " ⚠️ {$totalTidakAdaKelas} siswa tidak mendapat kelas tujuan (kelas belum dibuat) — status tetap aktif, harap assign kelas secara manual.";

                        Notification::make()
                            ->title('Naik Kelas Selesai (Dengan Peringatan)')
                            ->body($body)
                            ->warning()
                            ->duration(10000)
                            ->send();
                    } else {
                        Notification::make()
                            ->title('Kenaikan Kelas Berhasil! 🎓')
                            ->body($body)
                            ->success()
                            ->duration(8000)
                            ->send();
                    }
                });
            });
    }
}
