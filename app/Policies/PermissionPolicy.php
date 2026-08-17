<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Permission;

/**
 * PermissionPolicy
 *
 * Kebijakan sistem yang SANGAT KETAT.
 * Mengatur hak akses ke tabel Permission (Izin-izin tombol).
 * Supaya tidak ada yang iseng mengubah izin sistem, semuanya DIKUNCI (Return False).
 * Hanya Super Admin (yang bypass ini otomatis) yang bisa mengubahnya.
 */
class PermissionPolicy
{
    /**
     * Bolehkah melihat daftar izin?
     */
    public function viewAny(User $user): bool
    {
        return false; // Ditolak
    }

    /**
     * Bolehkah melihat detail izin?
     */
    public function view(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Bolehkah membuat izin baru?
     */
    public function create(User $user): bool
    {
        return false;
    }

    /**
     * Bolehkah mengubah izin?
     */
    public function update(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Bolehkah menghapus izin?
     */
    public function delete(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Bolehkah memulihkan izin?
     */
    public function restore(User $user, Permission $permission): bool
    {
        return false;
    }

    /**
     * Bolehkah menghapus permanen izin?
     */
    public function forceDelete(User $user, Permission $permission): bool
    {
        return false;
    }
}
