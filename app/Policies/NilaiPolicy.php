<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;

class NilaiPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Nilai');
    }

    public function view(User $user, Nilai $nilai): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah'])) {
            return true;
        }
        return $user->checkPermissionTo('view Nilai');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Nilai');
    }

    public function update(User $user, Nilai $nilai): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Nilai');
    }

    public function delete(User $user, Nilai $nilai): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Nilai');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Nilai');
    }

    public function restore(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('restore Nilai');
    }

    public function forceDelete(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('force-delete Nilai');
    }
}
