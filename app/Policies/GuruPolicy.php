<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Guru;
use App\Models\User;

class GuruPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Guru');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('view Guru');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Guru');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('update Guru');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('delete Guru');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Guru');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('restore Guru');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Guru');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('replicate Guru');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Guru');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Guru $guru): bool
    {
        return $user->checkPermissionTo('force-delete Guru');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Guru');
    }
}
