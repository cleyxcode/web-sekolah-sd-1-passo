<?php

// Namespace menunjukkan letak file ini di dalam folder project

namespace App\Http\Controllers;

/**
 * Controller Induk (Base Controller)
 *
 * Ini adalah kelas dasar yang disediakan oleh Laravel.
 * Semua controller lain (seperti HomeController, RaporController)
 * biasanya merupakan turunan (extends) dari kelas ini.
 *
 * Karena kelas ini bersifat 'abstract', kelas ini tidak bisa digunakan langsung,
 * melainkan hanya bisa diwariskan ke kelas lain.
 */
abstract class Controller
{
    // Kosong untuk saat ini.
    // Di sini kita bisa menambahkan fitur bawaan yang akan dipakai oleh semua controller.
}
