<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\OrangTua;
use App\Models\User;

class OrangTuaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any OrangTua');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('view OrangTua');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create OrangTua');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('update OrangTua');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('delete OrangTua');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any OrangTua');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('restore OrangTua');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any OrangTua');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('replicate OrangTua');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder OrangTua');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('force-delete OrangTua');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any OrangTua');
    }
}
