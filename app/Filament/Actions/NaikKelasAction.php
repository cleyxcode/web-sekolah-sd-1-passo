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
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Repeater;
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
            ->modalHeading('Proses Kenaikan Kelas')
            ->modalDescription(null)
            ->modalWidth('2xl')
            ->authorize(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->visible(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->form(function (): array {
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                if (!$tahunAjaranAktif) {
                    return [
                        Placeholder::make('no_ta')
                            ->label('')
                            ->content(new HtmlString(
                                '<div style="padding:14px;background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;">
                                    <strong>⚠️ Tidak ada tahun ajaran yang sedang aktif!</strong><br><br>
                                    Aktifkan satu tahun ajaran di menu <strong>Tahun Ajaran</strong> terlebih dahulu.
                                </div>'
                            )),
                    ];
                }

                // Ambil statistik kelas aktif
                $kelasGroups = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->orderBy('tingkat')
                    ->orderBy('nama_kelas')
                    ->get()
                    ->groupBy('tingkat');

                // Hitung siswa per kelas
                $siswaPerKelas = DB::table('siswas')
                    ->select('kelas_id', DB::raw('count(*) as total'))
                    ->where('status', 'aktif')
                    ->whereNotNull('kelas_id')
                    ->groupBy('kelas_id')
                    ->pluck('total', 'kelas_id');

                // Build preview HTML tabel
                $previewRows = '';
                $totalSiswa  = 0;
                $tingkatMax  = $kelasGroups->keys()->max() ?? 6;

                foreach ($kelasGroups as $tingkat => $kelasList) {
                    foreach ($kelasList as $kelas) {
                        $jml = $siswaPerKelas[$kelas->id] ?? 0;
                        $totalSiswa += $jml;
                        if ($tingkat >= $tingkatMax) {
                            $tujuanLabel = '<span style="color:#dc2626;font-weight:700;">Lulus / Alumni</span>';
                        } else {
                            $tujuanLabel = '<span style="color:#2563eb;font-weight:600;">Tingkat ' . ($tingkat + 1) . '</span>';
                        }
                        $previewRows .= "
                            <tr style='border-bottom:1px solid #f1f5f9;'>
                                <td style='padding:8px 12px;font-weight:600;'>Tingkat {$tingkat}</td>
                                <td style='padding:8px 12px;'>Kelas {$kelas->nama_kelas}</td>
                                <td style='padding:8px 12px;text-align:center;'>
                                    <span style='background:#dbeafe;color:#1e40af;padding:2px 10px;border-radius:99px;font-size:12px;font-weight:700;'>{$jml} siswa</span>
                                </td>
                                <td style='padding:8px 12px;'>→ {$tujuanLabel}</td>
                            </tr>
                        ";
                    }
                }

                $tingkatLulus = $tingkatMax;

                return [
                    // ── PREVIEW KELAS AKTIF ─────────────────────────────────
                    Placeholder::make('preview')
                        ->label('')
                        ->content(new HtmlString("
                            <div style='margin-bottom:16px;'>
                                <div style='font-weight:800;font-size:15px;color:#0f172a;margin-bottom:4px;'>📋 Kondisi Kelas Saat Ini</div>
                                <div style='color:#64748b;font-size:12px;'>Tahun Ajaran Aktif: <strong>{$tahunAjaranAktif->nama}</strong> &nbsp;|&nbsp; Total siswa aktif: <strong>{$totalSiswa}</strong></div>
                            </div>
                            <div style='border:1.5px solid #e2e8f0;border-radius:10px;overflow:hidden;'>
                                <table style='width:100%;border-collapse:collapse;font-size:13px;'>
                                    <thead>
                                        <tr style='background:#1e293b;'>
                                            <th style='padding:10px 12px;color:rgba(255,255,255,0.8);text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;'>Tingkat</th>
                                            <th style='padding:10px 12px;color:rgba(255,255,255,0.8);text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;'>Kelas</th>
                                            <th style='padding:10px 12px;color:rgba(255,255,255,0.8);text-align:center;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;'>Siswa</th>
                                            <th style='padding:10px 12px;color:rgba(255,255,255,0.8);text-align:left;font-size:11px;text-transform:uppercase;letter-spacing:0.05em;'>Tujuan</th>
                                        </tr>
                                    </thead>
                                    <tbody style='background:white;'>
                                        {$previewRows}
                                    </tbody>
                                </table>
                            </div>
                            <div style='margin-top:10px;padding:10px 14px;background:#fffbeb;border:1px solid #fcd34d;border-radius:8px;font-size:12px;color:#92400e;'>
                                <strong>ℹ️ Tingkat Tertinggi ({$tingkatLulus}):</strong>
                                Siswa di tingkat tertinggi akan ditandai <strong>Lulus/Alumni</strong> dan dilepas dari kelas secara otomatis.
                                Sekolah dengan satu kelas per tingkat maupun banyak kelas (A, B, C) semuanya didukung.
                            </div>
                        ")),

                    // ── MODE KENAIKAN ───────────────────────────────────────
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
                        ->helperText('Pilih "tahun ajaran yang sama" jika kelas tujuan sudah tersedia di sistem.'),

                    Select::make('tahun_ajaran_baru_id')
                        ->label('Pilih Tahun Ajaran Baru (Tujuan)')
                        ->options(
                            TahunAjaran::orderByDesc('nama')
                                ->where('id', '!=', $tahunAjaranAktif->id)
                                ->pluck('nama', 'id')
                        )
                        ->searchable()
                        ->preload()
                        ->required(fn ($get) => $get('mode') === 'new_tahun_ajaran')
                        ->visible(fn ($get) => $get('mode') === 'new_tahun_ajaran')
                        ->helperText('Pilih tahun ajaran yang sudah Anda buat untuk periode selanjutnya.'),

                    // ── STRATEGI MATCHING KELAS ─────────────────────────────
                    Select::make('strategi_mapping')
                        ->label('Strategi Penentuan Kelas Tujuan')
                        ->options([
                            'auto_first'   => '🤖 Otomatis — ambil kelas pertama di tingkat berikutnya',
                            'auto_suffix'  => '🔤 Otomatis — cocokkan huruf akhir kelas (A→A, B→B)',
                            'gabung_satu'  => '🔀 Gabung ke Satu Kelas — semua siswa dari satu tingkat masuk ke 1 kelas tujuan',
                        ])
                        ->default('auto_first')
                        ->required()
                        ->native(false)
                        ->live()
                        ->helperText('Sesuaikan dengan struktur kelas di sekolah Anda.'),

                    // Pilihan kelas target untuk mode "gabung_satu"
                    Placeholder::make('info_gabung')
                        ->label('')
                        ->content(new HtmlString(
                            '<div style="padding:10px 14px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;font-size:12px;color:#166534;">
                                <strong>ℹ️ Mode Gabung Satu Kelas:</strong><br>
                                Cocok untuk sekolah yang menggabungkan semua siswa dari beberapa kelas pararel ke satu kelas di tingkat berikutnya
                                (misal: semua siswa kelas 5A dan 5B digabung ke kelas 6 yang cuma ada 1).<br><br>
                                Sistem akan otomatis menemukan kelas tujuan pertama di setiap tingkat berikutnya.
                            </div>'
                        ))
                        ->visible(fn ($get) => $get('strategi_mapping') === 'gabung_satu'),

                    // ── KONFIRMASI ──────────────────────────────────────────
                    Checkbox::make('konfirmasi')
                        ->label('Saya memahami proses ini tidak bisa dibatalkan secara otomatis. Semua riwayat kelas akan tersimpan.')
                        ->required()
                        ->validationMessages(['required' => 'Centang kotak konfirmasi untuk melanjutkan.']),
                ];
            })
            ->modalSubmitActionLabel('✅ Proses Naik Kelas Sekarang')
            ->modalCancelActionLabel('Batal')
            ->action(function (array $data): void {
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                if (!$tahunAjaranAktif) {
                    Notification::make()->title('Gagal')->body('Tidak ada tahun ajaran aktif.')->danger()->send();
                    return;
                }

                $mode            = $data['mode'] ?? 'same_tahun_ajaran';
                $strategi        = $data['strategi_mapping'] ?? 'auto_first';
                $tahunTujuanId   = $mode === 'new_tahun_ajaran'
                    ? (int) $data['tahun_ajaran_baru_id']
                    : $tahunAjaranAktif->id;

                if ($mode === 'new_tahun_ajaran' && $tahunTujuanId === $tahunAjaranAktif->id) {
                    Notification::make()->title('Gagal')->body('Tahun ajaran tujuan tidak boleh sama dengan yang aktif.')->danger()->send();
                    return;
                }

                DB::transaction(function () use ($tahunAjaranAktif, $tahunTujuanId, $strategi) {
                    // Ambil semua kelas di tahun ajaran aktif
                    $kelasAktif = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                        ->orderBy('tingkat', 'desc')
                        ->orderBy('nama_kelas')
                        ->get();

                    // Tingkat tertinggi yang ada di sistem (fleksibel, tidak hardcode 6)
                    $tingkatMax = $kelasAktif->max('tingkat') ?? 6;

                    // Cache semua kelas tujuan per tingkat (untuk performa)
                    $kelasTujuanPerTingkat = Kelas::where('tahun_ajaran_id', $tahunTujuanId)
                        ->orderBy('nama_kelas')
                        ->get()
                        ->groupBy('tingkat');

                    $totalNaik          = 0;
                    $totalLulus         = 0;
                    $totalTanpaKelas    = 0;

                    foreach ($kelasAktif as $kelas) {
                        $siswas = Siswa::where('kelas_id', $kelas->id)
                            ->where('status', 'aktif')
                            ->get();

                        if ($siswas->isEmpty()) continue;

                        // ── TINGKAT TERTINGGI → LULUS ───────────────────
                        if ($kelas->tingkat >= $tingkatMax) {
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
                                    'tahun_ajaran_id' => $tahunTujuanId,
                                ]);
                                $totalLulus++;
                            }
                            continue;
                        }

                        // ── NAIK TINGKAT ────────────────────────────────
                        $tingkatBaru    = $kelas->tingkat + 1;
                        $kandidatKelas  = $kelasTujuanPerTingkat[$tingkatBaru] ?? collect();

                        $kelasTujuan = null;

                        if ($kandidatKelas->isNotEmpty()) {
                            if ($strategi === 'auto_suffix') {
                                // Cocokkan huruf/suffix dari nama_kelas
                                // Contoh: "1A" → "2A", "1" → "2", "Merah" → cari "Merah" di tingkat baru
                                $suffix = self::extractSuffix($kelas->nama_kelas);
                                if ($suffix !== '') {
                                    $kelasTujuan = $kandidatKelas->first(function ($k) use ($suffix) {
                                        return strtolower(self::extractSuffix($k->nama_kelas)) === strtolower($suffix);
                                    });
                                }
                                // Fallback: nama_kelas yang mengandung suffix
                                if (!$kelasTujuan) {
                                    $kelasTujuan = $kandidatKelas->first(function ($k) use ($suffix) {
                                        return $suffix !== '' && str_contains(strtolower($k->nama_kelas), strtolower($suffix));
                                    });
                                }
                            }

                            // auto_first, gabung_satu, atau fallback dari auto_suffix
                            if (!$kelasTujuan) {
                                $kelasTujuan = $kandidatKelas->first();
                            }
                        }

                        foreach ($siswas as $siswa) {
                            RiwayatKelas::create([
                                'siswa_id'        => $siswa->id,
                                'kelas_id'        => $kelas->id,
                                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                'status'          => 'naik',
                            ]);

                            $siswa->update([
                                'kelas_id'        => $kelasTujuan?->id,   // null jika tidak ada kelas di tingkat tsb
                                'tahun_ajaran_id' => $tahunTujuanId,
                                'status'          => 'aktif',             // SELALU aktif meski kelas null
                            ]);

                            $kelasTujuan ? $totalNaik++ : $totalTanpaKelas++;
                        }
                    }

                    // ── NOTIFIKASI HASIL ─────────────────────────────────
                    if ($totalNaik === 0 && $totalLulus === 0 && $totalTanpaKelas === 0) {
                        Notification::make()
                            ->title('Tidak Ada Perubahan')
                            ->body('Tidak ada siswa aktif di kelas manapun.')
                            ->warning()->send();
                        return;
                    }

                    $body = "{$totalNaik} siswa berhasil naik kelas. {$totalLulus} siswa dinyatakan lulus/alumni.";

                    if ($totalTanpaKelas > 0) {
                        $body .= " ⚠️ {$totalTanpaKelas} siswa tidak mendapat kelas tujuan — status tetap aktif, harap assign kelas secara manual.";
                        Notification::make()
                            ->title('Naik Kelas Selesai (Ada Peringatan)')
                            ->body($body)
                            ->warning()
                            ->duration(12000)
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

    /**
     * Ekstrak suffix/huruf dari nama kelas secara fleksibel.
     *
     * Contoh:
     *  "1A"     → "A"
     *  "2B"     → "B"
     *  "Merah"  → "Merah"   (tidak ada angka → nama adalah suffix-nya sendiri)
     *  "1"      → ""        (hanya angka → tidak ada suffix)
     *  "Kelas1" → ""
     *  "VI-A"   → "A"
     */
    private static function extractSuffix(string $namaKelas): string
    {
        // Hapus semua angka dan karakter non-huruf di depan
        $stripped = preg_replace('/^[\d\s\-\.]+/', '', $namaKelas);
        // Ambil bagian huruf saja
        $letters  = preg_replace('/[^a-zA-Z]/', '', $stripped);
        return $letters;
    }
}
