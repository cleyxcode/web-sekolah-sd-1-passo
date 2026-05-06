<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\ProfilSekolah;
use App\Models\User;

/**
 * ProfilSekolahPolicy
 * 
 * Mengatur hak akses ke menu Profil Sekolah (Sejarah, Visi, Misi).
 */
class ProfilSekolahPolicy
{
    /**
     * Bolehkah melihat pengaturan profil sekolah?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ProfilSekolah');
    }

    public function view(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('view ProfilSekolah');
    }

    /**
     * Bolehkah menulis profil sekolah baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ProfilSekolah');
    }

    /**
     * Bolehkah MENGEDIT Visi & Misi?
     */
    public function update(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('update ProfilSekolah');
    }

    public function delete(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('delete ProfilSekolah');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ProfilSekolah');
    }

    public function restore(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('restore ProfilSekolah');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ProfilSekolah');
    }

    public function replicate(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('replicate ProfilSekolah');
    }

    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ProfilSekolah');
    }

    public function forceDelete(User $user, ProfilSekolah $profilsekolah): bool
    {
        return $user->checkPermissionTo('force-delete ProfilSekolah');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ProfilSekolah');
    }
}
