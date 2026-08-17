<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model ActivityLog
 *
 * Mewakili tabel 'activity_logs' di database.
 * Satu baris = satu catatan aktivitas yang dilakukan oleh seorang pengguna (User).
 *
 * Activity log digunakan untuk melacak siapa melakukan apa dan kapan di dalam sistem.
 * Contoh: "Admin menghapus data siswa Budi pada 07 Mei 2026 pukul 08.30"
 *
 * Berguna untuk audit trail (jejak aktivitas) dan keamanan sistem.
 *
 * RELASI:
 *   - belongsTo User → Log ini dicatat untuk SATU pengguna (User)
 */
class ActivityLog extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: ActivityLog ini milik satu User (Many-to-One)
     * Kolom foreign key: 'user_id' di tabel activity_logs
     *
     * Cara pakai: $log->user → mengambil data pengguna yang melakukan aktivitas ini
     */
    public function user()
    {
        return $this->belongsTo(User::class); // belongsTo = "saya milik satu"
    }
}
