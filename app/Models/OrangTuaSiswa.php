<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project

namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model OrangTuaSiswa
 *
 * Mewakili tabel 'orang_tua_siswas' di database.
 * Ini adalah tabel PIVOT (penghubung) antara OrangTua dan Siswa.
 *
 * Tabel ini mencatat hubungan antara orang tua dan anak (siswa).
 * Satu orang tua bisa punya banyak anak, satu siswa bisa punya banyak orang tua.
 *
 * Contoh data:
 *   orang_tua_id=1 (Ibu Ani) → siswa_id=5 (Budi)
 *   orang_tua_id=2 (Bapak Budi) → siswa_id=5 (Budi)
 *
 * RELASI:
 *   - belongsTo OrangTua → Entri ini merujuk ke SATU orang tua
 *   - belongsTo Siswa    → Entri ini merujuk ke SATU siswa
 */
class OrangTuaSiswa extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: Entri pivot ini milik satu OrangTua (Many-to-One)
     * Kolom foreign key: 'orang_tua_id' di tabel orang_tua_siswas
     *
     * Cara pakai: $pivotEntry->orangTua → mengambil data orang tua dari entri ini
     */
    public function orangTua()
    {
        return $this->belongsTo(OrangTua::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Entri pivot ini milik satu Siswa (Many-to-One)
     * Kolom foreign key: 'siswa_id' di tabel orang_tua_siswas
     *
     * Cara pakai: $pivotEntry->siswa → mengambil data siswa dari entri ini
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class);
    }
}
