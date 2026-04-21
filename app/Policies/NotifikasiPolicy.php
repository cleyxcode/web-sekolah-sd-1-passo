<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Notifikasi;
use App\Models\User;

class NotifikasiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Notifikasi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('view Notifikasi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Notifikasi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('update Notifikasi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('delete Notifikasi');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Notifikasi');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('restore Notifikasi');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Notifikasi');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('replicate Notifikasi');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Notifikasi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('force-delete Notifikasi');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Notifikasi');
    }
}
