<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\KalenderAkademik;
use App\Models\User;

class KalenderAkademikPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any KalenderAkademik');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, KalenderAkademik $kalenderakademik): bool
    {
        return $user->checkPermissionTo('view KalenderAkademik');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create KalenderAkademik');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, KalenderAkademik $kalenderakademik): bool
    {
        return $user->checkPermissionTo('update KalenderAkademik');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, KalenderAkademik $kalenderakademik): bool
    {
        return $user->checkPermissionTo('delete KalenderAkademik');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any KalenderAkademik');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, KalenderAkademik $kalenderakademik): bool
    {
        return $user->checkPermissionTo('restore KalenderAkademik');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any KalenderAkademik');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, KalenderAkademik $kalenderakademik): bool
    {
        return $user->checkPermissionTo('replicate KalenderAkademik');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder KalenderAkademik');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, KalenderAkademik $kalenderakademik): bool
    {
        return $user->checkPermissionTo('force-delete KalenderAkademik');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any KalenderAkademik');
    }
}
