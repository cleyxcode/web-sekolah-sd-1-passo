<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

/**
 * SiswaPolicy
 *
 * Mengatur hak akses penuh terhadap data Siswa.
 */
class SiswaPolicy
{
    /**
     * Siapa yang boleh melihat DAFTAR siswa?
     */
    public function viewAny(User $user): bool
    {
        // Orang Tua dan Kepala Sekolah selalu diizinkan melihat daftar siswa
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }

        return $user->checkPermissionTo('view-any Siswa');
    }

    /**
     * Siapa yang boleh melihat DETAIL/PROFIL seorang siswa?
     */
    public function view(User $user, Siswa $siswa): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }

        return $user->checkPermissionTo('view Siswa');
    }

    /**
     * Siapa yang boleh MENDAFTARKAN / MENAMBAH siswa baru?
     */
    public function create(User $user): bool
    {
        // Kepsek bisa menambah siswa jika diizinkan
        if ($user->hasRole('Kepala Sekolah')) {
            return $user->checkPermissionTo('create Siswa');
        }
        // Orang Tua DILARANG KERAS menambah data siswa secara manual
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('create Siswa');
    }

    /**
     * Siapa yang boleh MENGEDIT data siswa (ganti nama, ganti kelas)?
     */
    public function update(User $user, Siswa $siswa): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('update Siswa');
    }

    /**
     * Siapa yang boleh MENGHAPUS data siswa?
     */
    public function delete(User $user, Siswa $siswa): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('delete Siswa');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('delete-any Siswa');
    }

    public function restore(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('restore Siswa');
    }

    public function forceDelete(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('force-delete Siswa');
    }

    /**
     * FUNGSI KHUSUS: Siapa yang boleh memproses tombol aksi "Naik Kelas" untuk satu siswa?
     * Hanya Admin & Kepala Sekolah.
     */
    public function naikKelas(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('naik-kelas');
    }
}
