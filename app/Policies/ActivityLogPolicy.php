<?php

namespace App\Policies;

use App\Models\ActivityLog;
use App\Models\User;

/**
 * ActivityLogPolicy
 *
 * Policy (Kebijakan) ini mengatur hak akses (siapa yang boleh melakukan apa)
 * terhadap tabel log aktivitas (catatan sejarah perubahan di dalam sistem).
 */
class ActivityLogPolicy
{
    /**
     * Apakah pengguna boleh melihat daftar log aktivitas?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any ActivityLog');
    }

    /**
     * Apakah pengguna boleh melihat detail satu log aktivitas tertentu?
     */
    public function view(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('view ActivityLog');
    }

    /**
     * Apakah pengguna boleh membuat log aktivitas manual?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create ActivityLog');
    }

    /**
     * Apakah pengguna boleh mengubah/mengedit data log aktivitas?
     */
    public function update(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('update ActivityLog');
    }

    /**
     * Apakah pengguna boleh menghapus satu log aktivitas?
     */
    public function delete(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('delete ActivityLog');
    }

    /**
     * Apakah pengguna boleh menghapus banyak log sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any ActivityLog');
    }

    /**
     * Apakah pengguna boleh mengembalikan log aktivitas yang sempat dihapus sementara (restore)?
     */
    public function restore(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('restore ActivityLog');
    }

    /**
     * Apakah pengguna boleh mengembalikan banyak log sekaligus?
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any ActivityLog');
    }

    /**
     * Apakah pengguna boleh menduplikasi (replicate) data log?
     */
    public function replicate(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('replicate ActivityLog');
    }

    /**
     * Apakah pengguna boleh mengubah urutan daftar log?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder ActivityLog');
    }

    /**
     * Apakah pengguna boleh menghapus log secara permanen (tidak bisa dikembalikan lagi)?
     */
    public function forceDelete(User $user, ActivityLog $activitylog): bool
    {
        return $user->checkPermissionTo('force-delete ActivityLog');
    }

    /**
     * Apakah pengguna boleh menghapus banyak log secara permanen?
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any ActivityLog');
    }
}
