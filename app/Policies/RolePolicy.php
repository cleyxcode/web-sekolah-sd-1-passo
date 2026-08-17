<?php

namespace App\Policies;

use App\Models\User;
use Spatie\Permission\Models\Role;

/**
 * RolePolicy
 *
 * Mengatur hak akses ke tabel Role (Jabatan/Peran seperti Admin, Guru, Kepsek).
 */
class RolePolicy
{
    /**
     * Bolehkah melihat daftar jabatan?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Role');
    }

    /**
     * Bolehkah melihat detail jabatan?
     */
    public function view(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('view Role');
    }

    /**
     * Bolehkah membuat jabatan baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Role');
    }

    /**
     * Bolehkah mengubah nama jabatan?
     */
    public function update(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('update Role');
    }

    /**
     * Bolehkah menghapus jabatan?
     */
    public function delete(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('delete Role');
    }

    /**
     * Bolehkah memulihkan jabatan yang terhapus?
     */
    public function restore(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('restore Role');
    }

    /**
     * Bolehkah menghapus jabatan selamanya?
     */
    public function forceDelete(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('force-delete Role');
    }
}
