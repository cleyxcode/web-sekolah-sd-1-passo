<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model SettingSekolah
 *
 * Mewakili tabel 'setting_sekolahs' di database.
 * Tabel ini biasanya hanya memiliki SATU baris data (konfigurasi global sekolah).
 *
 * Berisi semua informasi umum sekolah yang digunakan di seluruh sistem,
 * seperti: nama sekolah, alamat, nomor telepon, nama kepala sekolah, logo, dll.
 *
 * Cara mengambil data: SettingSekolah::first() → mengambil satu-satunya baris data
 *
 * Tidak memiliki relasi ke model lain.
 */
class SettingSekolah extends Model
{
    // $guarded = [] berarti semua kolom boleh diisi dari luar
    // Kolom-kolom yang biasanya ada: nama_sekolah, alamat, no_telepon,
    // kepala_sekolah, nip_kepala_sekolah, logo, kota, npsn, akreditasi, dll
    protected $guarded = [];
}
