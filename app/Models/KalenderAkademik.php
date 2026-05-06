<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model KalenderAkademik
 *
 * Mewakili tabel 'kalender_akademiks' di database.
 * Satu baris = satu kegiatan/event dalam kalender akademik sekolah.
 *
 * Contoh kegiatan: "Ujian Tengah Semester", "Libur Lebaran", "Penerimaan Rapor", dll.
 * Kalender ini ditampilkan di website maupun portal sekolah.
 *
 * RELASI:
 *   - belongsTo TahunAjaran → Kegiatan ini masuk dalam SATU tahun ajaran
 */
class KalenderAkademik extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: KalenderAkademik ini masuk dalam satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel kalender_akademiks
     *
     * Cara pakai: $kegiatan->tahunAjaran → mengambil data tahun ajaran dari kegiatan ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class); // belongsTo = "saya milik satu"
    }
}
