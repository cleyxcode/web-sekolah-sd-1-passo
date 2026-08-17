<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model MataPelajaran
 *
 * Mewakili tabel 'mata_pelajarans' di database.
 * Satu baris di tabel = satu mata pelajaran (contoh: Matematika, IPA, Bahasa Indonesia).
 *
 * RELASI:
 *   - hasMany Nilai           → Satu mapel bisa punya BANYAK data nilai
 *   - belongsToMany Guru      → Satu mapel bisa diajar oleh BANYAK guru (via pivot)
 *   - hasMany JadwalPelajaran → Satu mapel bisa masuk BANYAK jadwal pelajaran
 */
class MataPelajaran extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    // ─── RELASI ────────────────────────────────────────────

    /**
     * RELASI: MataPelajaran memiliki banyak Nilai (One-to-Many)
     * Artinya: satu mata pelajaran (contoh: Matematika) bisa punya banyak nilai,
     * satu untuk setiap siswa di setiap kelas dan semester
     *
     * Cara pakai: $mapel->nilais → semua nilai untuk mata pelajaran ini
     */
    public function nilais()
    {
        return $this->hasMany(Nilai::class); // hasMany = "saya punya banyak"
    }

    /**
     * RELASI: MataPelajaran diajar oleh banyak Guru (Many-to-Many)
     * Tabel penghubung (pivot): 'guru_mata_pelajarans'
     * Artinya: satu mata pelajaran bisa diajar oleh banyak guru (di kelas berbeda),
     * dan satu guru bisa mengajar banyak mata pelajaran
     *
     * Cara pakai: $mapel->gurus → semua guru yang mengajar mata pelajaran ini
     */
    public function gurus()
    {
        return $this->belongsToMany(Guru::class, 'guru_mata_pelajarans')
            ->withPivot(['kelas_id', 'tahun_ajaran_id']) // Ambil juga kolom tambahan dari tabel pivot
            ->withTimestamps(); // Simpan waktu created_at dan updated_at di pivot
    }

    /**
     * RELASI: MataPelajaran masuk dalam banyak JadwalPelajaran (One-to-Many)
     * Artinya: satu mata pelajaran bisa muncul di banyak jadwal (Senin jam 1, Rabu jam 3, dll)
     *
     * Cara pakai: $mapel->jadwalPelajarans → semua jadwal yang memuat mata pelajaran ini
     */
    public function jadwalPelajarans()
    {
        return $this->hasMany(JadwalPelajaran::class);
    }
}
