<?php

namespace App\Policies;

use App\Models\Guru;
use App\Models\User;

class GuruPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Guru');
    }

    public function view(User $user, Guru $guru): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Guru');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Guru'])) {
            return false;
        }
        return $user->checkPermissionTo('create Guru');
    }

    public function update(User $user, Guru $guru): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        // Guru hanya bisa edit data dirinya sendiri
        if ($user->hasRole('Guru')) {
            return $guru->user_id === $user->id;
        }
        return $user->checkPermissionTo('update Guru');
    }

    public function delete(User $user, Guru $guru): bool
    {
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
