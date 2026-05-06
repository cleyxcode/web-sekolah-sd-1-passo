<?php

// Mendeklarasikan bahwa file ini menggunakan PHP
// Namespace adalah alamat/lokasi file ini dalam project
namespace App\Models;

// Mengimpor kelas UserFactory untuk membuat data User palsu saat testing
use Database\Factories\UserFactory;

// Fillable = atribut yang boleh diisi secara massal (mass assignment)
use Illuminate\Database\Eloquent\Attributes\Fillable;

// Hidden = atribut yang disembunyikan saat data ditampilkan ke publik (JSON)
use Illuminate\Database\Eloquent\Attributes\Hidden;

// HasFactory = memungkinkan model ini menggunakan Factory untuk membuat data dummy
use Illuminate\Database\Eloquent\Factories\HasFactory;

// Authenticatable = kelas dasar Laravel untuk model yang bisa login
use Illuminate\Foundation\Auth\User as Authenticatable;

// Notifiable = memungkinkan model ini menerima notifikasi (email, SMS, dll)
use Illuminate\Notifications\Notifiable;

// FilamentUser = kontrak agar model ini bisa masuk ke panel admin Filament
use Filament\Models\Contracts\FilamentUser;

// Panel = representasi panel admin di Filament
use Filament\Panel;

// HasRoles = trait dari Spatie untuk mengelola peran (role) pengguna
use Spatie\Permission\Traits\HasRoles;

// HasSuperAdmin = trait tambahan agar Super Admin bisa melewati semua permission
use Althinect\FilamentSpatieRolesPermissions\Concerns\HasSuperAdmin;

// Daftar kolom di tabel 'users' yang boleh diisi dari luar (form, API, dll)
// name = nama pengguna, email = alamat email, password = kata sandi, is_active = status aktif
#[Fillable(['name', 'email', 'password', 'is_active'])]

// Kolom yang TIDAK akan ditampilkan ketika data dikirim sebagai JSON
// password dan remember_token disembunyikan demi keamanan
#[Hidden(['password', 'remember_token'])]

// Model User mewarisi semua fitur login dari Authenticatable
// dan mengimplementasikan FilamentUser agar bisa mengakses panel admin
class User extends Authenticatable implements FilamentUser
{
    // Menggunakan trait HasRoles (untuk role/permission) dan HasSuperAdmin (bypass permission)
    use HasRoles, HasSuperAdmin;

    /**
     * Menentukan apakah user ini boleh mengakses panel admin Filament
     * Syarat: kolom 'is_active' di database harus bernilai true (1)
     * Jika is_active = false/0, user tidak bisa masuk panel admin
     */
    public function canAccessPanel(Panel $panel): bool
    {
        return $this->is_active; // Kembalikan nilai is_active (true/false)
    }

    // Menggunakan HasFactory (untuk testing) dan Notifiable (untuk notifikasi)
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Mendefinisikan bagaimana kolom tertentu dikonversi (di-cast) ke tipe data PHP
     * Ini penting agar data yang dibaca dari database sudah dalam format yang benar
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            // Kolom 'email_verified_at' otomatis dikonversi menjadi objek Carbon (tanggal)
            'email_verified_at' => 'datetime',

            // Kolom 'password' otomatis di-hash saat disimpan (menggunakan bcrypt)
            'password' => 'hashed',
        ];
    }
}
