<?php

namespace App\Policies;

use App\Models\Guru;
use App\Models\User;

/**
 * GuruPolicy
 * 
 * Kebijakan hak akses untuk tabel Biodata Guru.
 */
class GuruPolicy
{
    /**
     * Menentukan siapa yang boleh melihat DAFTAR guru.
     */
    public function viewAny(User $user): bool
    {
        // Semua warga sekolah boleh melihat daftar guru
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Guru');
    }

    /**
     * Menentukan siapa yang boleh melihat DETAIL salah satu guru.
     */
    public function view(User $user, Guru $guru): bool
    {
        // Sama, semua boleh melihat profil guru
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Guru');
    }

    /**
     * Menentukan siapa yang boleh MENGINPUT data guru baru.
     */
    public function create(User $user): bool
    {
        // Orang Tua dan Guru dilarang menambahkan guru baru (Hanya Admin/Kepsek)
        if ($user->hasRole(['Orang Tua', 'Guru'])) {
            return false;
        }
        return $user->checkPermissionTo('create Guru');
    }

    /**
     * Menentukan siapa yang boleh MENGEDIT data guru.
     */
    public function update(User $user, Guru $guru): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        
        // FUNGSI KHUSUS: Guru HANYA bisa mengedit data dirinya sendiri!
        if ($user->hasRole('Guru')) {
            return $guru->user_id === $user->id;
        }
        
        return $user->checkPermissionTo('update Guru');
    }

    /**
     * Menentukan siapa yang boleh MENGHAPUS guru.
     */
    public function delete(User $user, Guru $guru): bool
    {
        // Guru tidak bisa menghapus dirinya sendiri, dan Orang Tua juga dilarang
        if ($user->hasRole(['Orang Tua', 'Guru'])) {
            return false;
        }
        return $user->checkPermissionTo('delete Guru');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Guru'])) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Guru');
    }

    public function restore(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('restore Guru');
    }

    public function forceDelete(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('force-delete Guru');
    }
}
