<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Galeri
 *
 * Mewakili tabel 'galeris' di database.
 * Satu baris = satu item foto/gambar di galeri website sekolah.
 *
 * Galeri menampilkan dokumentasi kegiatan sekolah, lomba, acara, dll.
 * Foto-foto ini ditampilkan di halaman publik website sekolah.
 *
 * RELASI:
 *   - belongsTo User → Foto ini diunggah oleh SATU pengguna (User)
 */
class Galeri extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Galeri ini diunggah oleh satu User (Many-to-One)
     * Kolom foreign key: 'user_id' di tabel galeris
     *
     * Cara pakai: $galeri->user → mengambil data pengguna yang mengunggah foto ini
     */
    public function user()
    {
        return $this->belongsTo(User::class); // belongsTo = "saya milik satu"
    }
}
