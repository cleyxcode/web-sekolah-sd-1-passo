<?php

namespace App\Policies;

use App\Models\Presensi;
use App\Models\User;

/**
 * PresensiPolicy
 *
 * Mengatur hak akses ke data Absensi / Presensi Siswa.
 */
class PresensiPolicy
{
    /**
     * Bolehkah melihat halaman absensi?
     */
    public function viewAny(User $user): bool
    {
        // Semua warga sekolah diizinkan (Admin, Kepsek, Guru, Orang Tua)
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    /**
     * Bolehkah melihat detail baris kehadiran tertentu?
     */
    public function view(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    /**
     * Bolehkah MENGISI / MELAKUKAN ABSENSI siswa?
     */
    public function create(User $user): bool
    {
        // Hanya Guru dan Admin yang boleh mengabsen siswa
        // Orang tua dan Kepsek cuma boleh mengecek/melihat hasilnya saja.
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Bolehkah MENGUBAH / MENGEDIT hasil absensi (jika salah klik)?
     */
    public function update(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Bolehkah MENGHAPUS data absensi?
     */
    public function delete(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function restore(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function forceDelete(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin');
    }
}
