<?php

namespace App\Policies;

use App\Models\GuruMataPelajaran;
use App\Models\User;

/**
 * GuruMataPelajaranPolicy
 *
 * Mengatur hak akses untuk data pembagian tugas mengajar guru.
 * (Guru A mengajar pelajaran apa saja di kelas mana saja)
 */
class GuruMataPelajaranPolicy
{
    /**
     * Siapa yang boleh melihat daftar tugas mengajar?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh melihat detail tugas mengajar tertentu?
     */
    public function view(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('view GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh membagikan tugas mengajar baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh mengubah pembagian tugas mengajar?
     */
    public function update(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('update GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus tugas mengajar seorang guru?
     */
    public function delete(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('delete GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus banyak tugas sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh mengembalikan data tugas mengajar yang terhapus?
     */
    public function restore(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('restore GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh mengembalikan banyak data tugas?
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh menyalin (duplikat) tugas mengajar?
     */
    public function replicate(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('replicate GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh menyusun ulang urutan tugas?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus permanen tugas mengajar?
     */
    public function forceDelete(User $user, GuruMataPelajaran $gurumatapelajaran): bool
    {
        return $user->checkPermissionTo('force-delete GuruMataPelajaran');
    }

    /**
     * Siapa yang boleh menghapus permanen banyak tugas sekaligus?
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any GuruMataPelajaran');
    }
}
