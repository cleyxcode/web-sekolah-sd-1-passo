<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Presensi;
use App\Models\User;

class PresensiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Presensi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('view Presensi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Presensi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('update Presensi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('delete Presensi');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Presensi');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('restore Presensi');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Presensi');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('replicate Presensi');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Presensi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Presensi $presensi): bool
    {
        return $user->checkPermissionTo('force-delete Presensi');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Presensi');
    }
}
