<?php

namespace App\Policies;

use App\Models\UploadDaftarHadir;
use App\Models\User;

/**
 * UploadDaftarHadirPolicy
 *
 * Kebijakan akses untuk mengelola unggahan bukti absensi (misal guru mengunggah foto daftar hadir manual).
 */
class UploadDaftarHadirPolicy
{
    /**
     * Bolehkah melihat daftar unggahan?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any UploadDaftarHadir');
    }

    /**
     * Bolehkah melihat detail unggahan/fotonya?
     */
    public function view(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('view UploadDaftarHadir');
    }

    /**
     * Bolehkah mengunggah bukti daftar hadir baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create UploadDaftarHadir');
    }

    /**
     * Bolehkah mengedit/mengubah file unggahan?
     */
    public function update(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('update UploadDaftarHadir');
    }

    /**
     * Bolehkah menghapus file yang diunggah?
     */
    public function delete(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('delete UploadDaftarHadir');
    }

    /**
     * Bolehkah menghapus banyak unggahan sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any UploadDaftarHadir');
    }

    /**
     * Bolehkah memulihkan unggahan yang terhapus?
     */
    public function restore(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('restore UploadDaftarHadir');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any UploadDaftarHadir');
    }

    /**
     * Bolehkah menggandakan (duplikat) unggahan?
     */
    public function replicate(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('replicate UploadDaftarHadir');
    }

    /**
     * Bolehkah mengurutkan ulang daftar unggahan?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder UploadDaftarHadir');
    }

    /**
     * Bolehkah menghapus permanen file unggahan daftar hadir?
     */
    public function forceDelete(User $user, UploadDaftarHadir $uploaddaftarhadir): bool
    {
        return $user->checkPermissionTo('force-delete UploadDaftarHadir');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any UploadDaftarHadir');
    }
}
