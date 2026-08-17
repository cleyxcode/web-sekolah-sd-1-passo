<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Notifikasi
 *
 * Mewakili tabel 'notifikasis' di database.
 * Satu baris = satu notifikasi yang dikirim ke seorang pengguna (User).
 *
 * Notifikasi digunakan untuk memberitahu pengguna tentang kejadian penting,
 * seperti nilai baru diinput, tugas baru, kehadiran, dll.
 *
 * RELASI:
 *   - belongsTo User → Notifikasi ini dikirim ke SATU pengguna (User)
 */
class Notifikasi extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Notifikasi ini dikirim kepada satu User (Many-to-One)
     * Kolom foreign key: 'user_id' di tabel notifikasis
     *
     * Cara pakai: $notifikasi->user → mengambil data pengguna penerima notifikasi ini
     */
    public function user()
    {
        return $this->belongsTo(User::class); // belongsTo = "saya milik satu"
    }
}
