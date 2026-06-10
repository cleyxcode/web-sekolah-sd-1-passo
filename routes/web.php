<?php

// Mengimpor class/alat bantuan bawaan Laravel untuk membuat rute URL
use Illuminate\Support\Facades\Route;

// Mengimpor Controller untuk mengatur logika tampilan publik website
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SitemapController;

/*
|--------------------------------------------------------------------------
| Web Routes (Jalur Alamat Website)
|--------------------------------------------------------------------------
|
| Di sinilah kita mendaftarkan semua link URL untuk aplikasi kita.
| Saat pengguna mengetikkan alamat di browser, Laravel akan mencari kecocokan
| link di bawah ini, lalu memanggil fungsi Controller yang sesuai.
|
*/


// ==========================================
// 1. ROUTE KHUSUS FILE STORAGE (GAMBAR/MEDIA)
// ==========================================
// Mengakali masalah pembacaan gambar (storage link) di server lokal/hosting
// Rute ini akan melayani permintaan URL berawalan /storage/...
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    // Cari lokasi asli file gambar di dalam folder storage internal Laravel
    $path = storage_path('app/public/' . $folder . '/' . $filename);

    // Jika file tidak ditemukan, tampilkan error 404 (Not Found)
    if (!file_exists($path)) {
        abort(404);
    }

    // Ambil isi file dan cari tahu tipe filenya (apakah .png, .jpg, dll)
    $file = file_get_contents($path);
    $type = mime_content_type($path);

    // Kembalikan gambar tersebut agar tampil di layar browser
    return response($file, 200)->header('Content-Type', $type);
})->where('folder', '.*')->where('filename', '.*');


// ==========================================
// 2. HALAMAN UTAMA / PUBLIK (Dilihat oleh Semua Orang)
// ==========================================

// Sitemap XML - peta website untuk mesin pencari Google
// URL: /sitemap.xml
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Jika pengunjung membuka halaman depan website (Contoh: webskolah.com/)
Route::get('/', [HomeController::class, 'index'])->name('home');

// Halaman Profil Sekolah
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');

// Halaman Daftar Berita / Pengumuman
Route::get('/berita', [HomeController::class, 'berita'])->name('berita.index');

// Halaman Baca Berita Secara Penuh (Berdasarkan link URL unik / slug)
Route::get('/berita/{berita:slug}', [HomeController::class, 'beritaDetail'])->name('berita.detail');

// Halaman Galeri (Foto / Video kegiatan sekolah)
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');

// Halaman Informasi Pendaftaran Siswa Baru
Route::get('/pendaftaran', [HomeController::class, 'pendaftaran'])->name('pendaftaran');


// ==========================================
// 3. PORTAL ORANG TUA (Khusus Wali Murid)
// ==========================================

// Menampilkan form halaman Login khusus orang tua
Route::get('/portal-ortu/login', [\App\Http\Controllers\PortalOrtuController::class, 'loginForm'])->name('login.ortu');

// Menerima data dari tombol Submit Login orang tua
Route::post('/portal-ortu/login', [\App\Http\Controllers\PortalOrtuController::class, 'authenticate'])->name('login.ortu.post');

// Proses Logout / Keluar Akun
Route::post('/portal-ortu/logout', [\App\Http\Controllers\PortalOrtuController::class, 'logout'])->name('logout.ortu');

// Halaman Dashboard Utama Orang Tua setelah berhasil login
Route::get('/portal-ortu', [\App\Http\Controllers\PortalOrtuController::class, 'dashboard'])->name('portal.ortu.dashboard');

// Halaman Edit Profil Orang Tua (Ganti password / nomor HP)
Route::get('/portal-ortu/profil', [\App\Http\Controllers\PortalOrtuController::class, 'profil'])->name('portal.ortu.profil');

// Proses menyimpan pembaruan (update) data profil Orang Tua
Route::put('/portal-ortu/profil', [\App\Http\Controllers\PortalOrtuController::class, 'updateProfil'])->name('portal.ortu.profil.update');


// ==========================================
// 4. ADMIN & GURU (Area Terproteksi Keamanan)
// ==========================================
// "middleware(['auth'])" artinya: Hanya pengguna yang sudah berhasil LOGIN yang boleh masuk ke link di dalam sini!

Route::middleware(['auth'])->group(function () {
    
    // Fitur mencetak Lembar E-Rapor milik 1 (satu) siswa tertentu
    Route::get('/admin/cetak-rapor/{nilai}', [\App\Http\Controllers\RaporController::class, 'cetakAdmin'])
        ->name('admin.cetak-rapor');

    // Fitur mencetak Rekap Nilai Seluruh Kelas (Ledger / Leger) dalam satu tabel besar
    Route::get('/admin/cetak-rekap-kelas', [\App\Http\Controllers\RekapKelasController::class, 'cetak'])
        ->name('admin.cetak-rekap-kelas');

});
