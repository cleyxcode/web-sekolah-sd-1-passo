<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model RiwayatKelas
 *
 * Mewakili tabel 'riwayat_kelas' di database.
 * Satu baris = satu catatan perpindahan kelas seorang siswa.
 *
 * Setiap kali siswa naik kelas, pindah kelas, atau diproses naik kelas,
 * data lama disimpan di sini sebagai riwayat/histori.
 *
 * Contoh: Siswa "Budi" di Kelas 1A tahun 2023/2024, naik ke Kelas 2A tahun 2024/2025.
 * Maka data "Budi - Kelas 1A - 2023/2024" disimpan di RiwayatKelas.
 *
 * RELASI:
 *   - belongsTo Siswa      → Riwayat ini milik SATU siswa
 *   - belongsTo Kelas      → Riwayat ini mencatat SATU kelas
 *   - belongsTo TahunAjaran → Riwayat ini untuk SATU tahun ajaran
 */
class RiwayatKelas extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    protected $guarded = [];

    /**
     * RELASI: RiwayatKelas ini milik satu Siswa (Many-to-One)
     * Kolom foreign key: 'siswa_id' di tabel riwayat_kelas
     *
     * Cara pakai: $riwayat->siswa → mengambil data siswa pemilik riwayat ini
     */
    public function siswa()
    {
        return $this->belongsTo(Siswa::class); // belongsTo = "saya milik satu"
    }

    /**
     * RELASI: RiwayatKelas mencatat satu Kelas (Many-to-One)
     * Kolom foreign key: 'kelas_id' di tabel riwayat_kelas
     *
     * Cara pakai: $riwayat->kelas → mengambil data kelas dari riwayat ini
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    /**
     * RELASI: RiwayatKelas terjadi di satu TahunAjaran (Many-to-One)
     * Kolom foreign key: 'tahun_ajaran_id' di tabel riwayat_kelas
     *
     * Cara pakai: $riwayat->tahunAjaran → mengambil data tahun ajaran dari riwayat ini
     */
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class);
    }
}
