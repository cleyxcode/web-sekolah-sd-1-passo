<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\DeskripsiKompetensi;
use App\Models\User;

class DeskripsiKompetensiPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('view DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('update DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('delete DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('restore DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('replicate DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('force-delete DeskripsiKompetensi');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any DeskripsiKompetensi');
    }
}
