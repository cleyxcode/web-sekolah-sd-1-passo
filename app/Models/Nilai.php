<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Nilai
 *
 * Mewakili tabel 'nilais' di database.
 * Satu baris di tabel = satu data nilai siswa untuk satu mata pelajaran.
 *
 * RELASI:
 *   - belongsTo Siswa          → Nilai ini milik SATU siswa
 *   - belongsTo MataPelajaran  → Nilai ini untuk SATU mata pelajaran
 *   - belongsTo Guru           → Nilai ini diinput oleh SATU guru
 *   - belongsTo Kelas          → Nilai ini berasal dari SATU kelas
 *   - belongsTo TahunAjaran   → Nilai ini untuk SATU tahun ajaran
 */
class Nilai extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar (tidak ada yang diproteksi)
    protected $guarded = [];

    // Mendefinisikan casting tipe data untuk setiap kolom penting
    protected $casts = [
        // Kolom 'nilai_angka' disimpan sebagai teks di database, tapi dibaca sebagai float (desimal)
        'nilai_angka'        => 'float',

        // Semua kolom ID ini akan otomatis dikonversi ke tipe integer (angka bulat)
        'tahun_ajaran_id'    => 'integer',
        'siswa_id'           => 'integer',
        'kelas_id'           => 'integer',
        'guru_id'            => 'integer',
        'mata_pelajaran_id'  => 'integer',
    ];

    // ─── RELASI ────────────────────────────────────────────

    /**
     * RELASI: Nilai ini milik satu Siswa (Many-to-One)
     * Artinya: banyak nilai bisa dimiliki oleh satu siswa
     * Kolom foreign key: 'siswa_id' di tabel nilais
     *
     * Cara pakai: $nilai->siswa → mengambil data siswa pemilik nilai ini
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Nilai ini untuk satu MataPelajaran (Many-to-One)
     * Kolom foreign key: 'mata_pelajaran_id' di tabel nilais
     *
     * Cara pakai: $nilai->mataPelajaran → mengambil data mata pelajaran dari nilai ini
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * RELASI: Nilai ini diinput oleh satu Guru (Many-to-One)
     * Biasanya guru yang menginput adalah wali kelas
     * Kolom foreign key: 'guru_id' di tabel nilais
     *
     * Cara pakai: $nilai->guru → mengambil data guru yang menginput nilai ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * RELASI: Nilai ini berasal dari satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel nilais
     *
     * Cara pakai: $nilai->kelas → mengambil data kelas dari nilai ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: Nilai ini untuk satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel nilais
     *
     * Cara pakai: $nilai->tahunAjaran → mengambil data tahun ajaran dari nilai ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    // ─── HELPERS ───────────────────────────────────────────

    /**
     * ACCESSOR: Menghitung predikat otomatis berdasarkan nilai angka
     * Accessor adalah properti "virtual" yang bisa dipanggil seperti kolom biasa
     *
     * Skala predikat:
     *   A = 90 - 100 (Sangat Baik)
     *   B = 75 - 89  (Baik)
     *   C = 60 - 74  (Cukup)
     *   D = 0  - 59  (Perlu Bimbingan)
     *
     * Cara pakai: $nilai->predikat → 'A', 'B', 'C', atau 'D'
     */
    public function getPredikatAttribute(): string
    {
        // match(true) = cocokkan kondisi yang pertama kali bernilai true
        return match(true) {
            $this->nilai_angka >= 90 => 'A', // Jika nilai >= 90, predikat A
            $this->nilai_angka >= 75 => 'B', // Jika nilai >= 75, predikat B
            $this->nilai_angka >= 60 => 'C', // Jika nilai >= 60, predikat C
            default                  => 'D', // Selain itu, predikat D
        };
    }

    /**
     * ACCESSOR: Menghasilkan keterangan teks berdasarkan predikat
     * Cara pakai: $nilai->keterangan → 'Sangat Baik', 'Baik', 'Cukup', atau 'Perlu Bimbingan'
     */
    public function getKeteranganAttribute(): string
    {
        // Cocokkan predikat dengan keterangan teks yang sesuai
        return match($this->predikat) {
            'A'     => 'Sangat Baik',     // Predikat A = Sangat Baik
            'B'     => 'Baik',            // Predikat B = Baik
            'C'     => 'Cukup',           // Predikat C = Cukup
            default => 'Perlu Bimbingan', // Predikat D = Perlu Bimbingan
        };
    }

    // ─── SCOPES ────────────────────────────────────────────

    /**
     * SCOPE: Filter nilai berdasarkan semester tertentu
     * Menggunakan 'nilais.semester' agar tidak ambigu jika ada JOIN dengan tabel lain
     *
     * Cara pakai: Nilai::semester('1')->get() → hanya nilai semester 1
     */
    public function scopeSemester($query, string $semester)
    {
        return $query->where('nilais.semester', $semester);
    }

    /**
     * SCOPE: Filter nilai berdasarkan jenis ujian tertentu (UTS atau UAS)
     * Menggunakan 'nilais.jenis_ujian' agar tidak ambigu jika ada JOIN
     *
     * Cara pakai: Nilai::jenisUjian('UTS')->get() → hanya nilai UTS
     */
    public function scopeJenisUjian($query, string $jenisUjian)
    {
        return $query->where('nilais.jenis_ujian', $jenisUjian);
    }

    /**
     * SCOPE: Filter nilai berdasarkan tahun ajaran tertentu
     * Menggunakan 'nilais.tahun_ajaran_id' agar tidak ambigu jika ada JOIN
     *
     * Cara pakai: Nilai::tahunAjaran(3)->get() → hanya nilai dari tahun ajaran id=3
     */
    public function scopeTahunAjaran($query, int $tahunAjaranId)
    {
        return $query->where('nilais.tahun_ajaran_id', $tahunAjaranId);
    }
}
