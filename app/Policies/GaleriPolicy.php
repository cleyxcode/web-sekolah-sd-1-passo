<?php

namespace App\Policies;

use App\Models\Galeri;
use App\Models\User;

class GaleriPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Galeri');
    }

    public function view(User $user, Galeri $galeri): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Galeri');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Galeri');
    }

    public function update(User $user, Galeri $galeri): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Galeri');
    }

    public function delete(User $user, Galeri $galeri): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Galeri');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Galeri');
    }

    public function restore(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('restore Galeri');
    }

    public function forceDelete(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('force-delete Galeri');
    }
}
