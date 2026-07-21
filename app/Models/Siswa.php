<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel, sebagai induk dari semua model
use Illuminate\Database\Eloquent\Model;

/**
 * Model Siswa
 *
 * Mewakili tabel 'siswas' di database.
 * Satu baris di tabel = satu data siswa.
 *
 * RELASI:
 *   - belongsTo Kelas         → Setiap siswa berada di SATU kelas
 *   - belongsTo TahunAjaran   → Setiap siswa terdaftar di SATU tahun ajaran
 *   - belongsToMany OrangTua  → Satu siswa bisa punya BANYAK orang tua (ayah, ibu, wali)
 *   - hasMany Nilai           → Satu siswa punya BANYAK data nilai
 *   - hasMany Presensi        → Satu siswa punya BANYAK catatan presensi
 *   - hasMany CatatanPerkembangan → Satu siswa punya BANYAK catatan perkembangan
 *   - hasMany RiwayatKelas    → Satu siswa punya BANYAK riwayat pindah kelas
 */
class Siswa extends Model
{
    // $guarded = [] berarti SEMUA kolom boleh diisi dari luar (tidak ada yang diproteksi)
    // Ini kebalikan dari $fillable yang hanya mengizinkan kolom tertentu
    protected $guarded = [];

    // ─── RELASI ────────────────────────────────────────────

    /**
     * RELASI: Siswa BERADA DI satu Kelas (Many-to-One)
     * Artinya: banyak siswa bisa ada di satu kelas yang sama
     * Kolom foreign key: 'kelas_id' di tabel siswas
     *
     * Cara pakai: $siswa->kelas → mengambil data kelas si siswa
     */
    public function kelas()
    {
        // belongsTo = "saya milik satu" → siswa milik satu kelas
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: Siswa terdaftar di satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel siswas
     *
     * Cara pakai: $siswa->tahunAjaran → mengambil data tahun ajaran si siswa
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * RELASI: Siswa memiliki banyak OrangTua / Wali (Many-to-Many)
     * Tabel penghubung (pivot): 'orang_tua_siswas'
     * Artinya: satu siswa bisa punya lebih dari satu orang tua, dan
     *          satu orang tua bisa punya lebih dari satu anak di sekolah ini
     *
     * Cara pakai: $siswa->orangTuas → koleksi semua orang tua si siswa
     */
    public function orangTuas()
    {
        return $this->belongsToMany(OrangTua::class, 'orang_tua_siswas')
            ->withTimestamps(); // Otomatis simpan waktu created_at dan updated_at di pivot
    }

    /**
     * RELASI: Siswa memiliki banyak Nilai (One-to-Many)
     * Artinya: satu siswa bisa punya banyak nilai (per mata pelajaran, per semester, dll)
     *
     * Cara pakai: $siswa->nilais → koleksi semua nilai si siswa
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class); // hasMany = "saya punya banyak"
    }

    /**
     * RELASI: Siswa memiliki banyak Presensi (One-to-Many)
     * Artinya: satu siswa bisa punya banyak catatan kehadiran (per hari)
     *
     * Cara pakai: $siswa->presensis → koleksi semua kehadiran si siswa
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    /**
     * RELASI: Siswa memiliki banyak CatatanPerkembangan (One-to-Many)
     * Artinya: guru bisa menulis banyak catatan perkembangan untuk satu siswa
     *
     * Cara pakai: $siswa->catatanPerkembangans → semua catatan perkembangan siswa
     */
    public function catatanPerkembangans()
    {
        return $this->hasMany(CatatanPerkembangan::class);
    }

    /**
     * RELASI: Siswa memiliki banyak RiwayatKelas (One-to-Many)
     * Artinya: setiap kali siswa naik kelas atau pindah kelas, dicatat di sini
     *
     * Cara pakai: $siswa->riwayatKelas → semua riwayat perpindahan kelas siswa
     */
    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class);
    }

    // ─── SCOPES ────────────────────────────────────────────

    /**
     * SCOPE: Filter hanya siswa yang statusnya 'aktif'
     * Scope adalah cara pintas untuk menyaring data dengan kondisi tertentu
     *
     * Cara pakai: Siswa::aktif()->get() → hanya ambil siswa yang aktif
     */
    public function scopeAktif($query)
    {
        // Tambahkan kondisi WHERE status = 'aktif' ke query
        return $query->where('status', 'aktif');
    }

    /**
     * HELPER: Menghitung rata-rata nilai siswa dengan filter opsional
     *
     * @param string|null $semester      Filter semester (contoh: '1' atau '2')
     * @param string|null $jenisUjian    Filter jenis ujian (contoh: 'UTS' atau 'UAS')
     * @param int|null    $tahunAjaranId Filter tahun ajaran tertentu
     * @return float Rata-rata nilai (angka desimal), 0 jika belum ada nilai
     *
     * Cara pakai: $siswa->getRataRataNilai('1', 'UTS', 5)
     */
    public function getRataRataNilai(?string $semester = null, ?string $jenisUjian = null, ?int $tahunAjaranId = null): float
    {
        // Mulai query dari relasi nilais (nilai milik siswa ini)
        $query = $this->nilais();

        // Jika ada filter semester, tambahkan kondisi where
        if ($semester)      $query->where('semester', $semester);

        // Jika ada filter jenis ujian, tambahkan kondisi where
        if ($jenisUjian)    $query->where('jenis_ujian', $jenisUjian);

        // Jika ada filter tahun ajaran, tambahkan kondisi where
        if ($tahunAjaranId) $query->where('tahun_ajaran_id', $tahunAjaranId);

        // Hitung rata-rata kolom nilai_angka, bulatkan 1 desimal
        // ?? 0 = jika hasilnya null (belum ada nilai), gunakan 0
        return round($query->avg('nilai_angka') ?? 0, 1);
    }
}
