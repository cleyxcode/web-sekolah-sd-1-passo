<?php

namespace App\Policies;

use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    public function viewAny(User $user): bool
    {
        // Orang Tua dan Kepala Sekolah boleh melihat daftar siswa
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Siswa');
    }

    public function view(User $user, Siswa $siswa): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view Siswa');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Kepala Sekolah')) {
            return $user->checkPermissionTo('create Siswa');
        }
        // Orang Tua tidak bisa create
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Siswa');
    }

    public function update(User $user, Siswa $siswa): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Siswa');
    }

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
}
