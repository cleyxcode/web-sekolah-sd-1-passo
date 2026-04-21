<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\GuruMataPelajaran;
use App\Models\User;

class GuruMataPelajaranPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any GuruMataPelajaran');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('view GuruMataPelajaran');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create GuruMataPelajaran');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('update GuruMataPelajaran');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('delete GuruMataPelajaran');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any GuruMataPelajaran');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('restore GuruMataPelajaran');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any GuruMataPelajaran');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('replicate GuruMataPelajaran');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder GuruMataPelajaran');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('force-delete GuruMataPelajaran');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any GuruMataPelajaran');
    }
}
