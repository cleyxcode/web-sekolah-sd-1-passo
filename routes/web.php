<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

// Storage route
Route::get('/storage/{folder}/{filename}', function ($folder, $filename) {
    $path = storage_path('app/public/' . $folder . '/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return response($file, 200)->header('Content-Type', $type);
})->where('folder', '.*')->where('filename', '.*');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [HomeController::class, 'profil'])->name('profil');
Route::get('/berita', [HomeController::class, 'berita'])->name('berita.index');
Route::get('/berita/{berita:slug}', [HomeController::class, 'beritaDetail'])->name('berita.detail');
Route::get('/galeri', [HomeController::class, 'galeri'])->name('galeri');
Route::get('/pendaftaran', [HomeController::class, 'pendaftaran'])->name('pendaftaran');

// Portal Orang Tua
Route::get('/portal-ortu/login', [\App\Http\Controllers\PortalOrtuController::class, 'loginForm'])->name('login.ortu');
Route::post('/portal-ortu/login', [\App\Http\Controllers\PortalOrtuController::class, 'authenticate'])->name('login.ortu.post');
Route::post('/portal-ortu/logout', [\App\Http\Controllers\PortalOrtuController::class, 'logout'])->name('logout.ortu');
Route::get('/portal-ortu', [\App\Http\Controllers\PortalOrtuController::class, 'dashboard'])->name('portal.ortu.dashboard');
Route::get('/portal-ortu/profil', [\App\Http\Controllers\PortalOrtuController::class, 'profil'])->name('portal.ortu.profil');
Route::put('/portal-ortu/profil', [\App\Http\Controllers\PortalOrtuController::class, 'updateProfil'])->name('portal.ortu.profil.update');




// Admin: Cetak E-Rapor per siswa (dilindungi auth)
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/cetak-rapor/{nilai}', [\App\Http\Controllers\RaporController::class, 'cetakAdmin'])
        ->name('admin.cetak-rapor');
});
