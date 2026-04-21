<?php

namespace App\Policies;

use App\Models\Presensi;
use App\Models\User;

class PresensiPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Presensi');
    }

    public function view(User $user, Presensi $presensi): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view Presensi');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Presensi');
    }

    public function update(User $user, Presensi $presensi): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Presensi');
    }

    public function delete(User $user, Presensi $presensi): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Presensi');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Presensi');
    }

    public function restore(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('restore Presensi');
    }

    public function forceDelete(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('force-delete Presensi');
    }
}
