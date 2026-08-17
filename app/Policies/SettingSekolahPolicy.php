<?php

namespace App\Policies;

use App\Models\SettingSekolah;
use App\Models\User;

/**
 * SettingSekolahPolicy
 *
 * Mengatur hak akses untuk konfigurasi sistem dan informasi dasar sekolah (Nama, Alamat, Logo).
 */
class SettingSekolahPolicy
{
    /**
     * Siapa yang boleh melihat daftar pengaturan sekolah?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any SettingSekolah');
    }

    /**
     * Siapa yang boleh melihat detail pengaturan sekolah?
     */
    public function view(User $user, SettingSekolah $settingsekolah): bool
    {
        return $user->checkPermissionTo('view SettingSekolah');
    }

    /**
     * Siapa yang boleh menambah pengaturan sekolah baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create SettingSekolah');
    }

    /**
     * Siapa yang boleh MENGUBAH / MENGEDIT data sekolah (misal ganti logo/kepsek)?
     */
    public function update(User $user, SettingSekolah $settingsekolah): bool
    {
        return $user->checkPermissionTo('update SettingSekolah');
    }

    /**
     * Siapa yang boleh MENGHAPUS pengaturan sekolah?
     */
    public function delete(User $user, SettingSekolah $settingsekolah): bool
    {
        return $user->checkPermissionTo('delete SettingSekolah');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any SettingSekolah');
    }

    public function restore(User $user, SettingSekolah $settingsekolah): bool
    {
        return $user->checkPermissionTo('restore SettingSekolah');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any SettingSekolah');
    }

    public function replicate(User $user, SettingSekolah $settingsekolah): bool
    {
        return $user->checkPermissionTo('replicate SettingSekolah');
    }

    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder SettingSekolah');
    }

    public function forceDelete(User $user, SettingSekolah $settingsekolah): bool
    {
        return $user->checkPermissionTo('force-delete SettingSekolah');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any SettingSekolah');
    }
}
