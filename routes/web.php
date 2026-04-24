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

// Lupa Password (OTP)
Route::get('/portal-ortu/forgot-password', [\App\Http\Controllers\PortalOrtuController::class, 'forgotPasswordForm'])->name('ortu.forgot_password');
Route::post('/portal-ortu/forgot-password', [\App\Http\Controllers\PortalOrtuController::class, 'sendOtp'])->name('ortu.forgot_password.post');
Route::get('/portal-ortu/verify-otp', [\App\Http\Controllers\PortalOrtuController::class, 'verifyOtpForm'])->name('ortu.verify_otp');
Route::post('/portal-ortu/verify-otp', [\App\Http\Controllers\PortalOrtuController::class, 'verifyOtp'])->name('ortu.verify_otp.post');
Route::get('/portal-ortu/reset-password', [\App\Http\Controllers\PortalOrtuController::class, 'resetPasswordForm'])->name('ortu.reset_password');
Route::post('/portal-ortu/reset-password', [\App\Http\Controllers\PortalOrtuController::class, 'resetPassword'])->name('ortu.reset_password.post');
