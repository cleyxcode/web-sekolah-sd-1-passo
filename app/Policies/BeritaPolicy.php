<?php

namespace App\Policies;

use App\Models\Berita;
use App\Models\User;

class BeritaPolicy
{
    public function viewAny(User $user): bool
    {
        // Semua role bisa melihat daftar berita
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Berita');
    }

    public function view(User $user, Berita $berita): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Berita');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Berita');
    }

    public function update(User $user, Berita $berita): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Berita');
    }

    public function delete(User $user, Berita $berita): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Berita');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Berita');
    }

    public function restore(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('restore Berita');
    }

    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Berita');
    }

    public function forceDelete(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('force-delete Berita');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Berita');
    }
}
