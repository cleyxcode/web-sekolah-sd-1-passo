<?php

namespace App\Policies;

use App\Models\User;

/**
 * UserPolicy
 *
 * Mengatur hak akses ke menu Manajemen Pengguna (Tabel Accounts Login).
 * (Siapa yang boleh buat akun, reset password, ganti role).
 */
class UserPolicy
{
    /**
     * Bolehkah melihat daftar semua pengguna sistem?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any User');
    }

    /**
     * Bolehkah melihat detail akun seseorang?
     */
    public function view(User $user, User $model): bool
    {
        return $user->checkPermissionTo('view User');
    }

    /**
     * Bolehkah membuat akun baru manual?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create User');
    }

    /**
     * Bolehkah mengedit data/password pengguna lain?
     */
    public function update(User $user, User $model): bool
    {
        return $user->checkPermissionTo('update User');
    }

    /**
     * Bolehkah menghapus/memblokir akun pengguna?
     */
    public function delete(User $user, User $model): bool
    {
        return $user->checkPermissionTo('delete User');
    }

    /**
     * Bolehkah menghapus banyak akun sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any User');
    }

    /**
     * Bolehkah memulihkan akun yang terhapus?
     */
    public function restore(User $user, User $model): bool
    {
        return $user->checkPermissionTo('restore User');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any User');
    }

    /**
     * Bolehkah menyalin akun (duplikat user)?
     */
    public function replicate(User $user, User $model): bool
    {
        return $user->checkPermissionTo('replicate User');
    }

    /**
     * Bolehkah menyusun ulang urutan pengguna?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder User');
    }

    /**
     * Bolehkah menghapus akun secara permanen (menghilangkan jejak)?
     */
    public function forceDelete(User $user, User $model): bool
    {
        return $user->checkPermissionTo('force-delete User');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any User');
    }
}
