<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TahunAjaran;
use App\Models\User;

class TahunAjaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TahunAjaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('view TahunAjaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TahunAjaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('update TahunAjaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('delete TahunAjaran');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TahunAjaran');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('restore TahunAjaran');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TahunAjaran');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('replicate TahunAjaran');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TahunAjaran');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('force-delete TahunAjaran');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TahunAjaran');
    }
}
