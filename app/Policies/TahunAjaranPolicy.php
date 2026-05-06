<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\TahunAjaran;
use App\Models\User;

/**
 * TahunAjaranPolicy
 * 
 * Mengatur akses ke menu pengaturan Tahun Ajaran & Semester aktif.
 */
class TahunAjaranPolicy
{
    /**
     * Bolehkah melihat daftar tahun ajaran?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any TahunAjaran');
    }

    /**
     * Bolehkah melihat detail tahun ajaran tertentu?
     */
    public function view(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('view TahunAjaran');
    }

    /**
     * Bolehkah membuat tahun ajaran baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create TahunAjaran');
    }

    /**
     * Bolehkah mengubah (mengaktifkan/menonaktifkan) tahun ajaran?
     */
    public function update(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('update TahunAjaran');
    }

    /**
     * Bolehkah menghapus tahun ajaran?
     */
    public function delete(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('delete TahunAjaran');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any TahunAjaran');
    }

    public function restore(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('restore TahunAjaran');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any TahunAjaran');
    }

    public function replicate(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('replicate TahunAjaran');
    }

    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder TahunAjaran');
    }

    public function forceDelete(User $user, TahunAjaran $tahunajaran): bool
    {
        return $user->checkPermissionTo('force-delete TahunAjaran');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any TahunAjaran');
    }
}
