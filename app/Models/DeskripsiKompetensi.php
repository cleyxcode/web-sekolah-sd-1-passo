<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model DeskripsiKompetensi
 *
 * Mewakili tabel 'deskripsi_kompetensis' di database.
 * Satu baris = satu deskripsi kompetensi siswa untuk satu mata pelajaran.
 *
 * Deskripsi kompetensi adalah keterangan kualitatif tentang kemampuan siswa
 * dalam suatu mata pelajaran (bukan hanya angka, tapi penjelasan teks).
 *
 * Contoh: "Andi sudah mampu melakukan penjumlahan dan pengurangan dengan baik,
 *          namun perlu berlatih lebih dalam konsep perkalian."
 *
 * RELASI:
 *   - belongsTo Siswa         → Deskripsi ini untuk SATU siswa
 *   - belongsTo MataPelajaran → Deskripsi ini untuk SATU mata pelajaran
 *   - belongsTo Guru          → Deskripsi ini ditulis oleh SATU guru
 *   - belongsTo Kelas         → Deskripsi ini di SATU kelas
 *   - belongsTo TahunAjaran   → Deskripsi ini di SATU tahun ajaran
 */
class DeskripsiKompetensi extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Deskripsi ini untuk satu Siswa (Many-to-One)
     * Kolom foreign key: 'siswa_id' di tabel deskripsi_kompetensis
     *
     * Cara pakai: $deskripsi->siswa → mengambil data siswa dari deskripsi ini
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Deskripsi ini untuk satu MataPelajaran (Many-to-One)
     * Kolom foreign key: 'mata_pelajaran_id' di tabel deskripsi_kompetensis
     *
     * Cara pakai: $deskripsi->mataPelajaran → mengambil data mata pelajaran dari deskripsi ini
     */
    public function mataPelajaran()
    {
        return $this->belongsTo(MataPelajaran::class);
    }

    /**
     * RELASI: Deskripsi ini ditulis oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel deskripsi_kompetensis
     *
     * Cara pakai: $deskripsi->guru → mengambil data guru yang menulis deskripsi ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }

    /**
     * RELASI: Deskripsi ini di satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel deskripsi_kompetensis
     *
     * Cara pakai: $deskripsi->kelas → mengambil data kelas dari deskripsi ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: Deskripsi ini di satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel deskripsi_kompetensis
     *
     * Cara pakai: $deskripsi->tahunAjaran → mengambil data tahun ajaran dari deskripsi ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
