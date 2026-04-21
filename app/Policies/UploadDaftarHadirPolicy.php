<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\UploadDaftarHadir;
use App\Models\User;

class UploadDaftarHadirPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any UploadDaftarHadir');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('view UploadDaftarHadir');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create UploadDaftarHadir');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('update UploadDaftarHadir');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('delete UploadDaftarHadir');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any UploadDaftarHadir');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('restore UploadDaftarHadir');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any UploadDaftarHadir');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('replicate UploadDaftarHadir');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder UploadDaftarHadir');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('force-delete UploadDaftarHadir');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any UploadDaftarHadir');
    }
}
