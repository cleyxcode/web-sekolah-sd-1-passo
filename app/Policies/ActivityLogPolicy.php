<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ActivityLog');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('view ActivityLog');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ActivityLog');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('update ActivityLog');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('delete ActivityLog');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ActivityLog');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('restore ActivityLog');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ActivityLog');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('replicate ActivityLog');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ActivityLog');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('force-delete ActivityLog');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ActivityLog');
    }
}
