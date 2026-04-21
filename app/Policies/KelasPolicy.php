<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Kelas;
use App\Models\User;

class KelasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Kelas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('view Kelas');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Kelas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('update Kelas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('delete Kelas');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Kelas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('restore Kelas');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Kelas');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('replicate Kelas');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Kelas');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('force-delete Kelas');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Kelas');
    }
}
