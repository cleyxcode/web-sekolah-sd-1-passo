<?php

// Namespace menunjukkan lokasi file ini dalam struktur folder project
namespace App\Models;

// Mengimpor HasFactory untuk membuat data dummy saat testing
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Mengimpor kelas Model dari Laravel sebagai induk dari model ini
use Illuminate\Database\Eloquent\Model;

/**
 * Model Pendaftaran
 *
 * Mewakili tabel 'pendaftarans' di database.
 * Satu baris = satu info pendaftaran siswa baru yang ditampilkan di website sekolah.
 *
 * Model ini digunakan untuk mengelola pengumuman/info pendaftaran siswa baru,
 * bukan data aplikasi pendaftar itu sendiri.
 *
 * Contoh: "Pendaftaran Siswa Baru Tahun Ajaran 2025/2026 dibuka mulai 1 Juni 2025.
 *          Klik link berikut untuk mendaftar: [link google form]"
 *
 * Tidak memiliki relasi ke model lain.
 */
class Pendaftaran extends Model
{
    // Mengaktifkan fitur Factory (untuk membuat data testing/dummy)
    use HasFactory;

    // Daftar kolom yang BOLEH diisi dari luar (whitelist menggunakan $fillable)
    protected $fillable = [
        'judul',            // Judul pengumuman pendaftaran
        'deskripsi',        // Penjelasan/deskripsi informasi pendaftaran
        'link_pendaftaran', // URL link formulir pendaftaran (contoh: Google Form)
        'is_active',        // Status aktif/nonaktif (apakah pengumuman ini ditampilkan)
    ];

    // Mendefinisikan casting tipe data untuk kolom tertentu
    protected $casts = [
        // Kolom 'is_active' disimpan sebagai 0/1 di database,
        // tapi saat dibaca PHP akan menjadi true/false (boolean)
        'is_active' => 'boolean',
    ];
}
