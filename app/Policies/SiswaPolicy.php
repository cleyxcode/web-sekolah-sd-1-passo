<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Siswa;
use App\Models\User;

class SiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Siswa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('view Siswa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Siswa');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('update Siswa');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('delete Siswa');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Siswa');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('restore Siswa');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Siswa');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('replicate Siswa');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Siswa');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Siswa $siswa): bool
    {
        return $user->checkPermissionTo('force-delete Siswa');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Siswa');
    }
}
