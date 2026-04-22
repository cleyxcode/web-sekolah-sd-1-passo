<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

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
