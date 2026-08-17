<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model TahunAjaran
 *
 * Mewakili tabel 'tahun_ajarans' di database.
 * Satu baris = satu tahun ajaran, contoh: "2024/2025".
 *
 * Tahun ajaran adalah periode aktif belajar mengajar dalam satu tahun.
 * Semua data (nilai, presensi, kelas, dll) terhubung ke tahun ajaran tertentu.
 *
 * RELASI:
 *   - hasMany Kelas → Satu tahun ajaran bisa memiliki BANYAK kelas
 */
class TahunAjaran extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: TahunAjaran memiliki banyak Kelas (One-to-Many)
     * Artinya: dalam satu tahun ajaran, bisa ada banyak kelas (1A, 1B, 2A, 2B, dst)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel kelas
     *
     * Cara pakai: $tahunAjaran->kelas → semua kelas di tahun ajaran ini
     */
    public function kelas()
    {
        return $this->hasMany(Kelas::class); // hasMany = "saya punya banyak"
    }
}
