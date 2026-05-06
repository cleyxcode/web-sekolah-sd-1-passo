<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\Kelas;
use App\Models\User;

/**
 * KelasPolicy
 * 
 * Mengatur hak akses ke menu pembagian Kelas & Rombongan Belajar.
 */
class KelasPolicy
{
    /**
     * Siapa yang boleh melihat daftar kelas?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Kelas');
    }

    /**
     * Siapa yang boleh melihat info satu kelas tertentu?
     */
    public function view(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('view Kelas');
    }

    /**
     * Siapa yang boleh membuat ruangan/kelas baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Kelas');
    }

    /**
     * Siapa yang boleh mengedit (misal: mengganti wali kelas)?
     */
    public function update(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('update Kelas');
    }

    /**
     * Siapa yang boleh menghapus kelas?
     */
    public function delete(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('delete Kelas');
    }

    /**
     * Siapa yang boleh menghapus banyak kelas sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Kelas');
    }

    /**
     * Siapa yang boleh mengembalikan kelas yang terhapus?
     */
    public function restore(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('restore Kelas');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any Kelas');
    }

    /**
     * Siapa yang boleh menyalin (duplikat) kelas?
     */
    public function replicate(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('replicate Kelas');
    }

    /**
     * Siapa yang boleh menyusun ulang urutan kelas?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Kelas');
    }

    public function forceDelete(User $user, Kelas $kelas): bool
    {
        return $user->checkPermissionTo('force-delete Kelas');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Kelas');
    }

    /**
     * FITUR KHUSUS: Siapa yang boleh menggunakan tombol "Kenaikan Kelas Otomatis"?
     * Hanya Admin Utama atau Kepala Sekolah yang diperbolehkan!
     */
    public function naikKelas(User $user): bool
    {
        return $user->checkPermissionTo('naik-kelas');
    }
}
