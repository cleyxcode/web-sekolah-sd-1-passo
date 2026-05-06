<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\RiwayatKelas;
use App\Models\User;

/**
 * RiwayatKelasPolicy
 * 
 * Mengatur hak akses siapa saja yang boleh melihat rekam jejak
 * historis siswa (tahun lalu dia kelas berapa, dll).
 */
class RiwayatKelasPolicy
{
    /**
     * Bolehkah melihat daftar riwayat?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any RiwayatKelas');
    }

    public function view(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('view RiwayatKelas');
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create RiwayatKelas');
    }

    public function update(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('update RiwayatKelas');
    }

    public function delete(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('delete RiwayatKelas');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any RiwayatKelas');
    }

    public function restore(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('restore RiwayatKelas');
    }

    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any RiwayatKelas');
    }

    public function replicate(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('replicate RiwayatKelas');
    }

    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder RiwayatKelas');
    }

    public function forceDelete(User $user, RiwayatKelas $riwayatkelas): bool
    {
        return $user->checkPermissionTo('force-delete RiwayatKelas');
    }

    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any RiwayatKelas');
    }
}
