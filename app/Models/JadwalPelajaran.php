<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model JadwalPelajaran
 *
 * Mewakili tabel 'jadwal_pelajarans' di database.
 * Satu baris = satu slot jadwal pelajaran.
 *
 * Contoh: "Matematika diajar Pak Budi di Kelas 3A setiap Senin jam 07.00 - 08.30"
 *
 * RELASI:
 *   - belongsTo Kelas         → Jadwal ini untuk SATU kelas
 *   - belongsTo MataPelajaran → Jadwal ini untuk SATU mata pelajaran
 *   - belongsTo Guru          → Jadwal ini diajar oleh SATU guru
 */
class JadwalPelajaran extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: JadwalPelajaran ini untuk satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel jadwal_pelajarans
     *
     * Cara pakai: $jadwal->kelas → mengambil data kelas dari jadwal ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: JadwalPelajaran ini untuk satu MataPelajaran (Many-to-One)
     * Kolom foreign key: 'mata_pelajaran_id' di tabel jadwal_pelajarans
     *
     * Cara pakai: $jadwal->mataPelajaran → mengambil data mata pelajaran dari jadwal ini
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * RELASI: JadwalPelajaran ini diajar oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel jadwal_pelajarans
     *
     * Cara pakai: $jadwal->guru → mengambil data guru pengajar dari jadwal ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
