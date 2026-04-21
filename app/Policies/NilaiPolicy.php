<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Nilai;
use App\Models\User;

class NilaiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Nilai');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('view Nilai');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Nilai');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('update Nilai');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('delete Nilai');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Nilai');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('restore Nilai');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Nilai');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('replicate Nilai');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Nilai');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Nilai $nilai): bool
    {
        return $user->checkPermissionTo('force-delete Nilai');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Nilai');
    }
}
