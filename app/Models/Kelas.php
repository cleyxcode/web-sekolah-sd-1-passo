<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Kelas
 *
 * Mewakili tabel 'kelas' di database.
 * Satu baris di tabel = satu data kelas (contoh: Kelas 1A, 2B, dst).
 *
 * RELASI:
 *   - belongsTo Guru (wali_kelas_id) → Setiap kelas punya SATU wali kelas (Guru)
 *   - belongsTo TahunAjaran          → Setiap kelas terdaftar di SATU tahun ajaran
 *   - hasMany Siswa                  → Satu kelas punya BANYAK siswa
 *   - hasMany JadwalPelajaran        → Satu kelas punya BANYAK jadwal pelajaran
 *   - hasMany Tugas                  → Satu kelas mendapat BANYAK tugas
 *   - hasMany Nilai                  → Satu kelas punya BANYAK data nilai
 *   - hasMany Presensi               → Satu kelas punya BANYAK catatan presensi
 */
class Kelas extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar (tidak ada yang diproteksi)
    protected $guarded = [];

    // ─── RELASI ────────────────────────────────────────────

    /**
     * RELASI: Kelas memiliki satu Wali Kelas (Guru) (Many-to-One)
     * Artinya: banyak kelas bisa memiliki wali kelas yang berbeda-beda
     * Kolom foreign key: 'wali_kelas_id' di tabel kelas (merujuk ke id di tabel gurus)
     *
     * Cara pakai: $kelas->waliKelas → mengambil data guru yang menjadi wali kelas ini
     */
    public function waliKelas()
    {
        // belongsTo = "saya milik satu"
        // Parameter kedua 'wali_kelas_id' = nama kolom foreign key di tabel kelas
        return $this->belongsTo(Guru::class, 'wali_kelas_id');
    }

    /**
     * RELASI: Kelas berada di satu TahunAjaran (Many-to-One)
     * Artinya: satu kelas (contoh: 1A) hanya ada di satu tahun ajaran tertentu
     * Kolom foreign key: 'tahun_ajaran_id' di tabel kelas
     *
     * Cara pakai: $kelas->tahunAjaran → mengambil data tahun ajaran kelas ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }

    /**
     * RELASI: Kelas memiliki banyak Siswa (One-to-Many)
     * Artinya: satu kelas bisa berisi banyak siswa
     * Kolom foreign key: 'kelas_id' di tabel siswas
     *
     * Cara pakai: $kelas->siswas → koleksi semua siswa di kelas ini
     */
    public function siswas()
    {
        return $this->hasMany(Siswa::class); // hasMany = "saya punya banyak"
    }

    /**
     * RELASI: Kelas memiliki banyak Siswa AKTIF saja (One-to-Many dengan filter)
     * Sama seperti siswas(), tapi hanya mengambil siswa dengan status 'aktif'
     *
     * Cara pakai: $kelas->siswasAktif → hanya siswa yang aktif di kelas ini
     */
    public function siswasAktif()
    {
        return $this->hasMany(Siswa::class)->where('status', 'aktif');
    }

    /**
     * RELASI: Kelas memiliki banyak JadwalPelajaran (One-to-Many)
     * Artinya: satu kelas bisa punya banyak jadwal pelajaran (per hari/jam)
     *
     * Cara pakai: $kelas->jadwalPelajarans → semua jadwal pelajaran kelas ini
     */
    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }

    /**
     * RELASI: Kelas mendapat banyak Tugas (One-to-Many)
     * Artinya: guru bisa memberikan banyak tugas untuk satu kelas
     *
     * Cara pakai: $kelas->tugas → semua tugas yang diberikan untuk kelas ini
     */
    public function tugas()
    {
        return $this->hasMany(Tugas::class);
    }

    /**
     * RELASI: Kelas memiliki banyak Nilai (One-to-Many)
     * Artinya: satu kelas punya banyak data nilai (untuk semua siswa dan mata pelajaran)
     *
     * Cara pakai: $kelas->nilais → semua nilai yang ada di kelas ini
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class);
    }

    /**
     * RELASI: Kelas memiliki banyak Presensi (One-to-Many)
     * Artinya: satu kelas punya banyak catatan kehadiran (per hari per siswa)
     *
     * Cara pakai: $kelas->presensis → semua catatan kehadiran di kelas ini
     */
    public function presensis()
    {
        return $this->hasMany(Presensi::class);
    }

    // ─── HELPERS ───────────────────────────────────────────

    /**
     * ACCESSOR: Menghasilkan label nama kelas yang lebih lengkap
     * Accessor adalah properti "virtual" yang bisa dipanggil seperti kolom biasa
     *
     * Cara pakai: $kelas->label → contoh output: "Kelas 1A (Tingkat 1)"
     */
    public function getLabelAttribute(): string
    {
        // Gabungkan nama kelas dengan tingkat jika ada
        return "Kelas {$this->nama_kelas}".($this->tingkat ? " (Tingkat {$this->tingkat})" : '');
    }

    /**
     * ACCESSOR: Menghitung jumlah siswa aktif di kelas ini
     * Cara pakai: $kelas->jumlah_siswa → angka jumlah siswa aktif
     */
    public function getJumlahSiswaAttribute(): int
    {
        // Hitung jumlah siswa yang aktif di kelas ini
        return $this->siswasAktif()->count();
    }
}
