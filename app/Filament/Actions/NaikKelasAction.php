<?php

namespace App\Filament\Actions;

use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Filament\Actions\Action;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Select;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

/**
 * NaikKelasAction
 *
 * Ini adalah tombol ajaib (Aksi) di pojok atas yang jika ditekan
 * akan menaikkan kelas SEMUA siswa secara serentak.
 * Contoh: Siswa kelas 1 naik ke kelas 2, kelas 6 otomatis jadi Alumni/Lulus.
 */
class NaikKelasAction extends Action
{
    /**
     * Nama identitas unik untuk tombol/aksi ini di sistem Filament.
     */
    public static function getDefaultName(): ?string
    {
        return 'naik_kelas';
    }

    /**
     * Pengaturan tampilan tombol dan jendela Popup (Modal) konfirmasinya.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            // Tampilan Tombol di pojok kanan atas
            ->label('Proses Naik Kelas')
            ->icon('heroicon-o-academic-cap') // Ikon topi toga kelulusan
            ->color('success') // Warna tombol hijau
            ->requiresConfirmation(false) // Kita buat modal form khusus, jadi matikan konfirmasi bawaan
            ->modalHeading('Proses Kenaikan Kelas')
            ->modalDescription(null)
            ->modalWidth('2xl') // Lebar jendela popup (modal)

            // Hak Akses: Tombol HANYA MUNCUL jika pengguna login punya izin 'naik-kelas'
            // (Ingat: di SiswaPolicy, ini cuma diizinkan untuk Admin & Kepsek)
            ->authorize(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)
            ->visible(fn (): bool => auth()->user()?->can('naik-kelas') ?? false)

            // --- ISI FORMULIR POPUP MODAL ---
            ->form(function (): array {
                // 1. Cek dulu, ada tahun ajaran yang sedang aktif nggak?
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();

                // Kalau tidak ada tahun ajaran aktif, blokir! (Suruh aktifkan dulu)
                if (! $tahunAjaranAktif) {
                    return [
                        TextEntry::make('no_ta')
                            ->label('')
                            ->state(new HtmlString(
                                '<div style="padding:14px;background:#fef2f2;border:1px solid #fca5a5;border-radius:10px;color:#991b1b;">
                                    <strong>⚠️ Tidak ada tahun ajaran yang sedang aktif!</strong><br><br>
                                    Aktifkan satu tahun ajaran di menu <strong>Tahun Ajaran</strong> terlebih dahulu.
                                </div>'
                            )),
                    ];
                }

                // 2. Kumpulkan data kelas yang ada saat ini (biar kelihatan siapa yang mau dinaikkan)
                $kelasGroups = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                    ->orderBy('tingkat')
                    ->orderBy('nama_kelas')
                    ->get()
                    ->groupBy('tingkat'); // Kelompokkan berdasarkan Kelas 1, Kelas 2, dst.

                // 3. Hitung jumlah anak (Siswa Aktif) di setiap kelas
                $siswaPerKelas = DB::table('siswas')
                    ->select('kelas_id', DB::raw('count(*) as total'))
                    ->where('status', 'aktif')
                    ->whereNotNull('kelas_id')
                    ->groupBy('kelas_id')
                    ->pluck('total', 'kelas_id');

                // 4. Susun bentuk tabel informasi HTML-nya
                $previewRows = '';
                $totalSiswa = 0;
                $tingkatMax = $kelasGroups->keys()->max() ?? 6; // Cari tahu kelas tertingginya (Biasanya 6)

                foreach ($kelasGroups as $tingkat => $kelasList) {
                    foreach ($kelasList as $kelas) {
                        $jml = $siswaPerKelas[$kelas->id] ?? 0;
                        $totalSiswa += $jml;

                        // Kalau dia sudah di kelas tertinggi (misal Kelas 6), tuliskan Lulus / Alumni
                        if ($tingkat >= $tingkatMax) {
                            $tujuanLabel = '<span style="color:#dc2626;font-weight:700;">Lulus / Alumni</span>';
                        } else {
                            $tujuanLabel = '<span style="color:#2563eb;font-weight:600;">Tingkat '.($tingkat + 1).'</span>';
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

                // Kembalikan kotak-kotak tampilan form ke layar
                return [
                    // ── BAGIAN A: PREVIEW KELAS AKTIF (Tabel Info) ─────────────────
                    TextEntry::make('preview')
                        ->label('')
                        ->state(new HtmlString("
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

                    // ── BAGIAN B: PENGATURAN MODE KENAIKAN ───────────────────────
                    Select::make('mode')
                        ->label('Mode Kenaikan Kelas')
                        ->options([
                            'same_tahun_ajaran' => '🔄 Naik kelas dalam tahun ajaran yang SAMA (pindah tingkat)',
                            'new_tahun_ajaran' => '📅 Naik kelas ke tahun ajaran BARU',
                        ])
                        ->default('same_tahun_ajaran')
                        ->required()
                        ->native(false)
                        ->live() // kalau ini diklik/berubah, kotak di bawahnya otomatis berubah
                        ->helperText('Pilih "tahun ajaran yang sama" jika kelas tujuan sudah tersedia di sistem.'),

                    // (Muncul kalau Mode nya "Tahun Ajaran BARU")
                    Select::make('tahun_ajaran_baru_id')
                        ->label('Pilih Tahun Ajaran Baru (Tujuan)')
                        ->options(
                            TahunAjaran::orderByDesc('nama')
                                ->where('id', '!=', $tahunAjaranAktif->id) // Jangan tampilkan TA yang aktif saat ini
                                ->pluck('nama', 'id')
                        )
                        ->searchable()
                        ->preload()
                        ->required(fn ($get) => $get('mode') === 'new_tahun_ajaran')
                        ->visible(fn ($get) => $get('mode') === 'new_tahun_ajaran')
                        ->helperText('Pilih tahun ajaran yang sudah Anda buat untuk periode selanjutnya.'),

                    // ── BAGIAN C: PENGATURAN LOGIKA KELAS ─────────────────────────
                    Select::make('strategi_mapping')
                        ->label('Strategi Penentuan Kelas Tujuan')
                        ->options([
                            'auto_first' => '🤖 Otomatis — ambil kelas pertama di tingkat berikutnya',
                            'auto_suffix' => '🔤 Otomatis — cocokkan huruf akhir kelas (1A ke 2A, 1B ke 2B)',
                            'gabung_satu' => '🔀 Gabung ke Satu Kelas — semua siswa pararel digabung ke 1 kelas',
                        ])
                        ->default('auto_first')
                        ->required()
                        ->native(false)
                        ->live()
                        ->helperText('Sesuaikan dengan struktur kelas di sekolah Anda.'),

                    // Penjelasan tambahan kalau pilih mode "gabung_satu"
                    TextEntry::make('info_gabung')
                        ->label('')
                        ->state(new HtmlString(
                            '<div style="padding:10px 14px;background:#f0fdf4;border:1px solid #86efac;border-radius:8px;font-size:12px;color:#166534;">
                                <strong>ℹ️ Mode Gabung Satu Kelas:</strong><br>
                                Cocok untuk sekolah yang menggabungkan semua siswa dari beberapa kelas pararel ke satu kelas di tingkat berikutnya
                                (misal: semua siswa kelas 5A dan 5B digabung ke kelas 6 yang cuma ada 1).<br><br>
                                Sistem akan otomatis menemukan kelas tujuan pertama di setiap tingkat berikutnya.
                            </div>'
                        ))
                        ->visible(fn ($get) => $get('strategi_mapping') === 'gabung_satu'),

                    // ── BAGIAN D: KONFIRMASI TERAKHIR ─────────────────────────────
                    Checkbox::make('konfirmasi')
                        ->label('Saya memahami proses ini tidak bisa dibatalkan secara otomatis. Semua riwayat kelas akan tersimpan.')
                        ->required()
                        ->validationMessages(['required' => 'Centang kotak konfirmasi untuk melanjutkan.']),
                ];
            })
            // Tombol Hijau
            ->modalSubmitActionLabel('✅ Proses Naik Kelas Sekarang')
            // Tombol Abu-abu
            ->modalCancelActionLabel('Batal')

            // --- APA YANG TERJADI KETIKA TOMBOL "Proses Naik Kelas" DITEKAN? ---
            ->action(function (array $data): void {

                // Pastikan masih ada Tahun Ajaran Aktif
                $tahunAjaranAktif = TahunAjaran::where('is_active', true)->first();
                if (! $tahunAjaranAktif) {
                    Notification::make()->title('Gagal')->body('Tidak ada tahun ajaran aktif.')->danger()->send();

                    return;
                }

                // Ambil semua isian dari kotak pilihan tadi
                $mode = $data['mode'] ?? 'same_tahun_ajaran';
                $strategi = $data['strategi_mapping'] ?? 'auto_first';

                // Tentukan Tahun Ajaran tujuan. Kalau mode "sama", ya pakai TA saat ini
                $tahunTujuanId = $mode === 'new_tahun_ajaran'
                    ? (int) $data['tahun_ajaran_baru_id']
                    : $tahunAjaranAktif->id;

                // Cegah kebodohan: Mode nya Tahun Baru, tapi milih tahun yang sama dengan yang aktif
                if ($mode === 'new_tahun_ajaran' && $tahunTujuanId === $tahunAjaranAktif->id) {
                    Notification::make()->title('Gagal')->body('Tahun ajaran tujuan tidak boleh sama dengan yang aktif.')->danger()->send();

                    return;
                }

                // GUNAKAN DATABASE TRANSACTION:
                // Jika di tengah jalan tiba-tiba internet mati / error, semua data di-Batal-kan!
                // Mencegah ada murid yang tertinggal separuh.
                DB::transaction(function () use ($tahunAjaranAktif, $tahunTujuanId, $strategi) {

                    // Ambil seluruh kelas dari tingkat tertingi sampai terendah
                    // MENGAPA DARI TERTINGGI (Misal kelas 6 duluan)?
                    // Agar kelas 6 kosong dulu, baru kemudian diisi oleh anak kelas 5 yang naik.
                    $kelasAktif = Kelas::where('tahun_ajaran_id', $tahunAjaranAktif->id)
                        ->orderBy('tingkat', 'desc')
                        ->orderBy('nama_kelas')
                        ->get();

                    // Cari tahu tingkat tertingginya berapa
                    $tingkatMax = $kelasAktif->max('tingkat') ?? 6;

                    // Siapkan daftar kelas tujuan, kelompokkan biar gampang dicarinya nanti
                    $kelasTujuanPerTingkat = Kelas::where('tahun_ajaran_id', $tahunTujuanId)
                        ->orderBy('nama_kelas')
                        ->get()
                        ->groupBy('tingkat');

                    // Variabel penghitung statistik akhir
                    $totalNaik = 0;
                    $totalLulus = 0;
                    $totalTanpaKelas = 0;

                    // == LOOP 1: PROSES SETIAP KELAS ==
                    foreach ($kelasAktif as $kelas) {

                        // Ambil daftar siswanya (hanya yang statusnya masih AKTIF)
                        $siswas = Siswa::where('kelas_id', $kelas->id)
                            ->where('status', 'aktif')
                            ->get();

                        // Kalau kelas ini kosong/tidak ada muridnya, lewati! Lanjut ke kelas lain.
                        if ($siswas->isEmpty()) {
                            continue;
                        }

                        // ── KONDISI 1: JIKA INI KELAS TERTINGGI (Misal Kelas 6) ───────────────────
                        if ($kelas->tingkat >= $tingkatMax) {

                            // Luluskan semua anak di kelas ini
                            foreach ($siswas as $siswa) {
                                // 1. Catat ke tabel riwayat, anak ini sudah "Lulus" dari kelas 6
                                RiwayatKelas::create([
                                    'siswa_id' => $siswa->id,
                                    'kelas_id' => $kelas->id,
                                    'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                    'status' => 'lulus',
                                ]);

                                // 2. Update status utama siswa jadi "Lulus", copot ID Kelasnya (null)
                                $siswa->update([
                                    'status' => 'lulus',
                                    'kelas_id' => null,
                                    'tahun_ajaran_id' => $tahunTujuanId,
                                ]);
                                $totalLulus++;
                            }

                            // Stop sampai sini untuk kelas tertinggi. Lanjut kelas selanjutnya.
                            continue;
                        }

                        // ── KONDISI 2: JIKA INI KELAS BAWAH (KELAS 1 SD 5) NAIK TINGKAT ────────────
                        $tingkatBaru = $kelas->tingkat + 1; // Contoh: asalnya tingkat 2 + 1 = mau ke tingkat 3
                        $kandidatKelas = $kelasTujuanPerTingkat[$tingkatBaru] ?? collect(); // Daftar kelas 3 apa saja

                        $kelasTujuan = null; // Ini "keranjang" tujuan tempat kita lempar murid-murid ini

                        // CARI KELAS TUJUANNYA BERDASARKAN STRATEGI TADI
                        if ($kandidatKelas->isNotEmpty()) {

                            // Strategi: Cocokkan huruf abjad akhir (Misal kelas awal 2B, berarti cari 3B)
                            if ($strategi === 'auto_suffix') {
                                // Ambil huruf di ujung namanya. "2B" diambil "B" nya saja.
                                $suffix = self::extractSuffix($kelas->nama_kelas);

                                if ($suffix !== '') {
                                    // Cari kelas di tingkat 3 yang huruf belakangnya "B" juga
                                    $kelasTujuan = $kandidatKelas->first(function ($k) use ($suffix) {
                                        return strtolower(self::extractSuffix($k->nama_kelas)) === strtolower($suffix);
                                    });
                                }
                                // Kalau "3B" itu gak ketemu, tapi ada "Tiga B", coba cari yang ada tulisan B nya
                                if (! $kelasTujuan) {
                                    $kelasTujuan = $kandidatKelas->first(function ($k) use ($suffix) {
                                        return $suffix !== '' && str_contains(strtolower($k->nama_kelas), strtolower($suffix));
                                    });
                                }
                            }

                            // Kalau strateginya auto_first atau gabung_satu ATAU auto_suffix tadi nggak nemu pasangannya,
                            // Langsung masukkan saja ke kelas pertama yang tersedia di tingkat tersebut.
                            if (! $kelasTujuan) {
                                $kelasTujuan = $kandidatKelas->first();
                            }
                        }

                        // SETELAH KETEMU KELAS TUJUANNYA, PINDAHKAN MURIDNYA SATU-SATU
                        foreach ($siswas as $siswa) {

                            // 1. Simpan rekam jejak, kalau siswa ini berhasil "Naik" dari kelas lamanya.
                            RiwayatKelas::create([
                                'siswa_id' => $siswa->id,
                                'kelas_id' => $kelas->id,
                                'tahun_ajaran_id' => $tahunAjaranAktif->id,
                                'status' => 'naik',
                            ]);

                            // 2. Timpa data lama dengan kelas yang baru. (Kalau gak ketemu kelasnya, null dulu)
                            $siswa->update([
                                'kelas_id' => $kelasTujuan?->id,
                                'tahun_ajaran_id' => $tahunTujuanId,
                                'status' => 'aktif', // Status SELALU aktif meski kelasnya kosong
                            ]);

                            // 3. Tambah skor laporan
                            $kelasTujuan ? $totalNaik++ : $totalTanpaKelas++;
                        }
                    }

                    // ── MUNCULKAN NOTIFIKASI LAPORAN (SUKSES/GAGAL) KETIKA SELESAI ─────────────────

                    // Kalau ternyata semua kosong, nggak ada yang dinaikkan
                    if ($totalNaik === 0 && $totalLulus === 0 && $totalTanpaKelas === 0) {
                        Notification::make()
                            ->title('Tidak Ada Perubahan')
                            ->body('Tidak ada siswa aktif di kelas manapun.')
                            ->warning()->send();

                        return;
                    }

                    $body = "{$totalNaik} siswa berhasil naik kelas. {$totalLulus} siswa dinyatakan lulus/alumni.";

                    // Jika ada anak yang naik, TAPI kelas di atasnya BELUM DIBUAT
                    if ($totalTanpaKelas > 0) {
                        $body .= " ⚠️ {$totalTanpaKelas} siswa tidak mendapat kelas tujuan — status tetap aktif, harap assign kelas secara manual.";
                        Notification::make()
                            ->title('Naik Kelas Selesai (Ada Peringatan)')
                            ->body($body)
                            ->warning()
                            ->duration(12000)
                            ->send();
                    } else {
                        // Kalau semua murid mulus pindah ke kelas barunya dengan selamat
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
     * FUNGSI ALAT BANTU (Helper)
     * Untuk mengambil huruf di belakang angka kelas.
     *
     * Contoh:
     *  "1A"     → "A"
     *  "2B"     → "B"
     *  "Merah"  → "Merah"   (tidak ada angka → ambil kata)
     *  "1"      → ""        (hanya angka → kosong)
     */
    private static function extractSuffix(string $namaKelas): string
    {
        // Hilangkan angka, spasi, tanda strip di awal kata
        $stripped = preg_replace('/^[\d\s\-\.]+/', '', $namaKelas);

        // Cuma sisakan murni huruf abjadnya saja
        $letters = preg_replace('/[^a-zA-Z]/', '', $stripped);

        return $letters;
    }
}
