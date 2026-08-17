<?php

namespace App\Policies;

use App\Models\JadwalPelajaran;
use App\Models\User;

/**
 * JadwalPelajaranPolicy
 *
 * Mengatur hak akses ke data Jadwal Pelajaran (siapa mengajar di kelas mana, jam berapa).
 */
class JadwalPelajaranPolicy
{
    /**
     * Bolehkah melihat daftar jadwal?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any JadwalPelajaran');
    }

    /**
     * Bolehkah melihat detail satu jadwal?
     */
    public function view(User $user, JadwalPelajaran $jadwalpelajaran): bool
    {
        return $user->checkPermissionTo('view JadwalPelajaran');
    }

    /**
     * Bolehkah membuat jadwal baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create JadwalPelajaran');
    }

    /**
     * Bolehkah mengedit jadwal?
     */
    public function update(User $user, JadwalPelajaran $jadwalpelajaran): bool
    {
        return $user->checkPermissionTo('update JadwalPelajaran');
    }

    /**
     * Bolehkah menghapus jadwal?
     */
    public function delete(User $user, JadwalPelajaran $jadwalpelajaran): bool
    {
        return $user->checkPermissionTo('delete JadwalPelajaran');
    }

    /**
     * Bolehkah menghapus banyak jadwal sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any JadwalPelajaran');
    }

    /**
     * Bolehkah memulihkan jadwal yang dihapus?
     */
    public function restore(User $user, JadwalPelajaran $jadwalpelajaran): bool
    {
        return $user->checkPermissionTo('restore JadwalPelajaran');
    }

    /**
     * Bolehkah memulihkan banyak jadwal?
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any JadwalPelajaran');
    }

    /**
     * Bolehkah menggandakan (duplikat) jadwal?
     */
    public function replicate(User $user, JadwalPelajaran $jadwalpelajaran): bool
    {
        return $user->checkPermissionTo('replicate JadwalPelajaran');
    }

    /**
     * Bolehkah mengubah urutan tampil jadwal?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder JadwalPelajaran');
    }

    /**
     * Bolehkah menghapus permanen jadwal?
     */
    public function forceDelete(User $user, JadwalPelajaran $jadwalpelajaran): bool
    {
        return $user->checkPermissionTo('force-delete JadwalPelajaran');
    }

    /**
     * Bolehkah menghapus permanen banyak jadwal?
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any JadwalPelajaran');
    }
}
