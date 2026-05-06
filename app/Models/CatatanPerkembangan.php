<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor HasFactory untuk membuat data dummy saat testing
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model CatatanPerkembangan
 *
 * Mewakili tabel 'catatan_perkembangans' di database.
 * Satu baris = satu catatan perkembangan yang ditulis guru untuk satu siswa.
 *
 * Catatan perkembangan berisi komentar kualitatif tentang sikap, karakter,
 * atau perkembangan non-akademik siswa.
 *
 * RELASI:
 *   - belongsTo Siswa → Catatan ini ditulis untuk SATU siswa
 *   - belongsTo Guru  → Catatan ini ditulis oleh SATU guru
 */
class CatatanPerkembangan extends Model
{
    // Mengaktifkan fitur Factory (untuk membuat data testing/dummy)
    use HasFactory;

    // Daftar kolom yang BOLEH diisi dari luar (whitelist)
    // Berbeda dengan $guarded, $fillable lebih aman karena kita eksplisit menyebut kolomnya
    protected $fillable = [
        'siswa_id',  // ID siswa yang mendapat catatan ini
        'guru_id',   // ID guru yang menulis catatan ini
        'predikat',  // Predikat/nilai kualitatif (misal: A, B, C atau Baik, Cukup, dsb)
        'catatan',   // Isi teks catatan perkembangan siswa
    ];

    /**
     * RELASI: Catatan ini ditulis untuk satu Siswa (Many-to-One)
     * Kolom foreign key: 'siswa_id' di tabel catatan_perkembangans
     *
     * Cara pakai: $catatan->siswa → mengambil data siswa penerima catatan ini
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: Catatan ini ditulis oleh satu Guru (Many-to-One)
     * Kolom foreign key: 'guru_id' di tabel catatan_perkembangans
     *
     * Cara pakai: $catatan->guru → mengambil data guru yang menulis catatan ini
     */
    public function guru()
    {
        return $this->belongsTo(Guru::class);
    }
}
