<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Rapor;
use App\Models\User;

class RaporPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Rapor');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('view Rapor');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Rapor');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('update Rapor');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('delete Rapor');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Rapor');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('restore Rapor');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Rapor');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('replicate Rapor');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Rapor');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Rapor $rapor): bool
    {
        return $user->checkPermissionTo('force-delete Rapor');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Rapor');
    }
}
