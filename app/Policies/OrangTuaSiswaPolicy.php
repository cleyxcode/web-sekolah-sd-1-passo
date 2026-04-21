<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\OrangTuaSiswa;
use App\Models\User;

class OrangTuaSiswaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any OrangTuaSiswa');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('view OrangTuaSiswa');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create OrangTuaSiswa');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('update OrangTuaSiswa');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('delete OrangTuaSiswa');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any OrangTuaSiswa');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('restore OrangTuaSiswa');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any OrangTuaSiswa');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('replicate OrangTuaSiswa');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder OrangTuaSiswa');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('force-delete OrangTuaSiswa');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any OrangTuaSiswa');
    }
}
