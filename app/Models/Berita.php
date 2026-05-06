<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Berita
 *
 * Mewakili tabel 'beritas' di database.
 * Satu baris = satu artikel berita/pengumuman yang ditampilkan di website sekolah.
 *
 * Berita dibuat oleh pengguna (admin/guru) dan ditampilkan di halaman publik website.
 *
 * RELASI:
 *   - belongsTo User → Berita ini ditulis oleh SATU pengguna (User)
 */
class Berita extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Berita ini ditulis oleh satu User (Many-to-One)
     * Kolom foreign key: 'user_id' di tabel beritas
     *
     * Cara pakai: $berita->user → mengambil data pengguna yang membuat berita ini
     */
    public function user()
    {
        return $this->belongsTo(User::class); // belongsTo = "saya milik satu"
    }
}
