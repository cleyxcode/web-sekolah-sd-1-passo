<?php

namespace App\Policies;

use App\Models\OrangTuaSiswa;
use App\Models\User;

/**
 * OrangTuaSiswaPolicy
 *
 * Mengatur hak akses relasi/sambungan antara Orang Tua dan anak-anaknya (Siswa).
 * (Menentukan anak mana terhubung ke ayah/ibu siapa).
 */
class OrangTuaSiswaPolicy
{
    /**
     * Bolehkah melihat daftar relasi orang tua dan anak?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any OrangTuaSiswa');
    }

    /**
     * Bolehkah melihat detail relasi tertentu?
     */
    public function view(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('view OrangTuaSiswa');
    }

    /**
     * Bolehkah membuat relasi baru (menyambungkan ayah dengan anaknya)?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create OrangTuaSiswa');
    }

    /**
     * Bolehkah mengedit/mengubah relasi tersebut?
     */
    public function update(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('update OrangTuaSiswa');
    }

    /**
     * Bolehkah memutuskan (menghapus) relasi orang tua dan anak?
     */
    public function delete(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('delete OrangTuaSiswa');
    }

    /**
     * Bolehkah memutuskan banyak relasi sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any OrangTuaSiswa');
    }

    /**
     * Bolehkah memulihkan relasi yang terhapus sementara?
     */
    public function restore(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('restore OrangTuaSiswa');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any OrangTuaSiswa');
    }

    /**
     * Bolehkah menggandakan relasi?
     */
    public function replicate(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('replicate OrangTuaSiswa');
    }

    /**
     * Bolehkah menggeser/menyusun ulang urutannya?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder OrangTuaSiswa');
    }

    /**
     * Bolehkah menghapus relasi selamanya?
     */
    public function forceDelete(User $user, OrangTuaSiswa $orangtuasiswa): bool
    {
        return $user->checkPermissionTo('force-delete OrangTuaSiswa');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any OrangTuaSiswa');
    }
}
