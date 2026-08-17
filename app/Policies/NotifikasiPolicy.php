<?php

namespace App\Policies;

use App\Models\Notifikasi;
use App\Models\User;

/**
 * NotifikasiPolicy
 *
 * Mengatur hak akses siapa saja yang boleh melihat atau mengelola tabel Notifikasi (pesan sistem).
 */
class NotifikasiPolicy
{
    /**
     * Siapa yang boleh melihat daftar notifikasi?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Notifikasi');
    }

    /**
     * Siapa yang boleh membaca detail satu notifikasi?
     */
    public function view(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('view Notifikasi');
    }

    /**
     * Siapa yang boleh membuat/mengirim notifikasi manual?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Notifikasi');
    }

    /**
     * Siapa yang boleh mengedit isi notifikasi?
     */
    public function update(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('update Notifikasi');
    }

    /**
     * Siapa yang boleh menghapus notifikasi?
     */
    public function delete(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('delete Notifikasi');
    }

    /**
     * Siapa yang boleh menghapus banyak notifikasi?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Notifikasi');
    }

    /**
     * Siapa yang boleh memulihkan notifikasi yang terhapus?
     */
    public function restore(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('restore Notifikasi');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Notifikasi');
    }

    /**
     * Siapa yang boleh menyalin (duplikat) notifikasi?
     */
    public function replicate(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('replicate Notifikasi');
    }

    /**
     * Siapa yang boleh menyusun ulang urutan notifikasi?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Notifikasi');
    }

    /**
     * Siapa yang boleh menghapus permanen notifikasi?
     */
    public function forceDelete(User $user, Notifikasi $notifikasi): bool
    {
        return $user->checkPermissionTo('force-delete Notifikasi');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Notifikasi');
    }
}
