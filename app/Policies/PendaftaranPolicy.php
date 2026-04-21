<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

class PendaftaranPolicy
{
    public function viewAny(User $user): bool
    {
        // Semua user boleh melihat daftar link pendaftaran
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Pendaftaran');
    }

    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Pendaftaran');
    }

    public function create(User $user): bool
    {
        // Hanya Admin yang bisa create
        return $user->checkPermissionTo('create Pendaftaran');
    }

    public function update(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('update Pendaftaran');
    }

    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('delete Pendaftaran');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pendaftaran');
    }

    public function restore(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('restore Pendaftaran');
    }

    public function forceDelete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('force-delete Pendaftaran');
    }
}
