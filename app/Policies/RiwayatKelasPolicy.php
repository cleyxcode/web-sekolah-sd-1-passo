<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\RiwayatKelas;
use App\Models\User;

class RiwayatKelasPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any RiwayatKelas');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('view RiwayatKelas');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create RiwayatKelas');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('update RiwayatKelas');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('delete RiwayatKelas');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any RiwayatKelas');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('restore RiwayatKelas');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any RiwayatKelas');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('replicate RiwayatKelas');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder RiwayatKelas');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('force-delete RiwayatKelas');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any RiwayatKelas');
    }
}
