<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model KomentarTugas
 *
 * Mewakili tabel 'komentar_tugas' di database.
 * Satu baris = satu komentar yang diberikan guru pada sebuah tugas.
 *
 * Guru bisa memberikan komentar/feedback pada tugas yang dikirim siswa.
 *
 * RELASI:
 *   - belongsTo Tugas → Komentar ini untuk SATU tugas
 *   - belongsTo Guru  → Komentar ini ditulis oleh SATU guru
 */
class KomentarTugas extends Model
{
    // Nama tabel di database dideklarasikan secara eksplisit
    // karena nama default Laravel untuk model ini adalah 'komentar_tugas' (bukan 'komentar_tugas' plural otomatis)
    protected $table = 'komentar_tugas';

    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Komentar ini untuk satu Tugas (Many-to-One)
     * Kolom foreign key: 'tugas_id' di tabel komentar_tugas
     *
     * Cara pakai: $komentar->tugas → mengambil data tugas dari komentar ini
     */
    public function tugas()
    {
        return $this->belongsTo(Tugas::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Komentar ini ditulis oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel komentar_tugas
     *
     * Cara pakai: $komentar->guru → mengambil data guru penulis komentar ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
