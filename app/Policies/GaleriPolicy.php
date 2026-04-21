<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Galeri;
use App\Models\User;

class GaleriPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Galeri');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('view Galeri');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Galeri');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('update Galeri');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('delete Galeri');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Galeri');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('restore Galeri');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Galeri');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('replicate Galeri');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Galeri');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('force-delete Galeri');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Galeri');
    }
}
