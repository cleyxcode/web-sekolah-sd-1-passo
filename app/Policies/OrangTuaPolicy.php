<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\OrangTua;
use App\Models\User;

/**
 * OrangTuaPolicy
 * 
 * Kebijakan akses untuk mengelola akun dan data Orang Tua wali murid.
 */
class OrangTuaPolicy
{
    /**
     * Bolehkah melihat daftar Orang Tua?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any OrangTua');
    }

    /**
     * Bolehkah melihat detail biodata satu Orang Tua?
     */
    public function view(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('view OrangTua');
    }

    /**
     * Bolehkah membuat/mendaftarkan akun Orang Tua baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create OrangTua');
    }

    /**
     * Bolehkah mengedit data/password Orang Tua?
     */
    public function update(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('update OrangTua');
    }

    /**
     * Bolehkah menghapus data Orang Tua?
     */
    public function delete(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('delete OrangTua');
    }

    /**
     * Bolehkah menghapus banyak data Orang Tua sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any OrangTua');
    }

    /**
     * Bolehkah memulihkan data yang tak sengaja terhapus?
     */
    public function restore(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('restore OrangTua');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any OrangTua');
    }

    /**
     * Bolehkah menggandakan (duplikat) data Orang Tua?
     */
    public function replicate(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('replicate OrangTua');
    }

    /**
     * Bolehkah menyusun ulang urutan?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder OrangTua');
    }

    /**
     * Bolehkah menghapus data Orang Tua selamanya (permanen)?
     */
    public function forceDelete(User $user, OrangTua $orangtua): bool
    {
        return $user->checkPermissionTo('force-delete OrangTua');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any OrangTua');
    }
}
