<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model UploadDaftarHadir
 *
 * Mewakili tabel 'upload_daftar_hadirs' di database.
 * Satu baris = satu file daftar hadir (absensi) yang diunggah oleh guru.
 *
 * Guru dapat mengunggah file (PDF/Excel) daftar hadir fisik ke sistem.
 * File ini bisa digunakan sebagai arsip digital atau bukti kehadiran.
 *
 * RELASI:
 *   - belongsTo Guru        → File ini diunggah oleh SATU guru
 *   - belongsTo Kelas       → File ini untuk SATU kelas
 *   - belongsTo TahunAjaran → File ini untuk SATU tahun ajaran
 */
class UploadDaftarHadir extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: UploadDaftarHadir ini diunggah oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel upload_daftar_hadirs
     *
     * Cara pakai: $upload->guru → mengambil data guru yang mengunggah file ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: UploadDaftarHadir ini untuk satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel upload_daftar_hadirs
     *
     * Cara pakai: $upload->kelas → mengambil data kelas dari file upload ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: UploadDaftarHadir ini untuk satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel upload_daftar_hadirs
     *
     * Cara pakai: $upload->tahunAjaran → mengambil data tahun ajaran dari file upload ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
