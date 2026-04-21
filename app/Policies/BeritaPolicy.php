<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Berita;
use App\Models\User;

class BeritaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Berita');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('view Berita');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Berita');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('update Berita');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('delete Berita');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Berita');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('restore Berita');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Berita');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('replicate Berita');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Berita');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('force-delete Berita');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Berita');
    }
}
