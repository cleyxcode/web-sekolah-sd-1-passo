<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model GuruMataPelajaran
 *
 * Mewakili tabel 'guru_mata_pelajarans' di database.
 * Ini adalah tabel PIVOT (penghubung) antara Guru, MataPelajaran, Kelas, dan TahunAjaran.
 *
 * Satu baris = satu penugasan guru untuk mengajar satu mata pelajaran
 * di kelas tertentu dan tahun ajaran tertentu.
 *
 * Contoh: "Pak Budi mengajar Matematika di Kelas 3A tahun ajaran 2024/2025"
 *
 * RELASI:
 *   - belongsTo Guru         → Penugasan ini untuk SATU guru
 *   - belongsTo MataPelajaran → Penugasan ini untuk SATU mata pelajaran
 *   - belongsTo Kelas        → Penugasan ini di SATU kelas
 *   - belongsTo TahunAjaran  → Penugasan ini di SATU tahun ajaran
 */
class GuruMataPelajaran extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Penugasan ini milik satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel guru_mata_pelajarans
     *
     * Cara pakai: $penugasan->guru → mengambil data guru dari penugasan ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Penugasan ini untuk satu MataPelajaran (Many-to-One)
     * Kolom foreign key: 'mata_pelajaran_id' di tabel guru_mata_pelajarans
     *
     * Cara pakai: $penugasan->mataPelajaran → mengambil data mata pelajaran dari penugasan ini
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * RELASI: Penugasan ini di satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel guru_mata_pelajarans
     *
     * Cara pakai: $penugasan->kelas → mengambil data kelas dari penugasan ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: Penugasan ini di satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel guru_mata_pelajarans
     *
     * Cara pakai: $penugasan->tahunAjaran → mengambil data tahun ajaran dari penugasan ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
