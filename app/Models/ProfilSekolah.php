<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model ProfilSekolah
 *
 * Mewakili tabel 'profil_sekolahs' di database.
 * Berisi informasi profil sekolah yang ditampilkan di halaman "Tentang Sekolah"
 * pada website publik sekolah.
 *
 * Berbeda dengan SettingSekolah yang berisi konfigurasi teknis,
 * ProfilSekolah berisi konten deskriptif seperti:
 * sejarah sekolah, visi, misi, dan informasi umum lainnya.
 *
 * Tidak memiliki relasi ke model lain.
 */
class ProfilSekolah extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    // Kolom yang biasanya ada: visi, misi, sejarah, sambutan_kepala, dll
    protected $guarded = [];
}
