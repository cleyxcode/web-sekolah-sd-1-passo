<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProfilSekolah;
use App\Models\User;

class ProfilSekolahPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProfilSekolah');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('view ProfilSekolah');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProfilSekolah');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('update ProfilSekolah');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('delete ProfilSekolah');
    }

    /**
     * Determine whether the user can delete any models.
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ProfilSekolah');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('restore ProfilSekolah');
    }

    /**
     * Determine whether the user can restore any models.
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ProfilSekolah');
    }

    /**
     * Determine whether the user can replicate the model.
     */
    public function replicate(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('replicate ProfilSekolah');
    }

    /**
     * Determine whether the user can reorder the models.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ProfilSekolah');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('force-delete ProfilSekolah');
    }

    /**
     * Determine whether the user can permanently delete any models.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ProfilSekolah');
    }
}
