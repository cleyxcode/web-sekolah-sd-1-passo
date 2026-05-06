<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\MataPelajaran;
use App\Models\User;

/**
 * MataPelajaranPolicy
 * 
 * Mengatur hak akses ke menu Mata Pelajaran.
 */
class MataPelajaranPolicy
{
    /**
     * Siapa yang boleh melihat daftar mata pelajaran?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any MataPelajaran');
    }

    /**
     * Siapa yang boleh melihat detail spesifik mata pelajaran?
     */
    public function view(User $user, MataPelajaran $matapelajaran): bool
    {
        return $user->checkPermissionTo('view MataPelajaran');
    }

    /**
     * Siapa yang boleh membuat/menambahkan mata pelajaran baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create MataPelajaran');
    }

    /**
     * Siapa yang boleh mengedit mata pelajaran?
     */
    public function update(User $user, MataPelajaran $matapelajaran): bool
    {
        return $user->checkPermissionTo('update MataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus mata pelajaran?
     */
    public function delete(User $user, MataPelajaran $matapelajaran): bool
    {
        return $user->checkPermissionTo('delete MataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus banyak mata pelajaran sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any MataPelajaran');
    }

    /**
     * Siapa yang boleh memulihkan mata pelajaran yang terhapus?
     */
    public function restore(User $user, MataPelajaran $matapelajaran): bool
    {
        return $user->checkPermissionTo('restore MataPelajaran');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any MataPelajaran');
    }

    /**
     * Siapa yang boleh menggandakan (duplikat) mata pelajaran?
     */
    public function replicate(User $user, MataPelajaran $matapelajaran): bool
    {
        return $user->checkPermissionTo('replicate MataPelajaran');
    }

    /**
     * Siapa yang boleh mengubah urutan daftar mata pelajaran?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder MataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus mata pelajaran secara permanen?
     */
    public function forceDelete(User $user, MataPelajaran $matapelajaran): bool
    {
        return $user->checkPermissionTo('force-delete MataPelajaran');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any MataPelajaran');
    }
}
