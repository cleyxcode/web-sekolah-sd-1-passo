<?php

namespace App\Policies;

use App\Models\Rapor;
use App\Models\User;

class RaporPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Rapor');
    }

    public function view(User $user, Rapor $rapor): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view Rapor');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Rapor');
    }

    public function update(User $user, Rapor $rapor): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Rapor');
    }

    public function delete(User $user, Rapor $rapor): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Rapor');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Rapor');
    }

    public function restore(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('restore Rapor');
    }

    public function forceDelete(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('force-delete Rapor');
    }
}
