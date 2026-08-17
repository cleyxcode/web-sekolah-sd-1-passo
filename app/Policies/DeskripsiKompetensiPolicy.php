<?php

namespace App\Policies;

use App\Models\DeskripsiKompetensi;
use App\Models\User;

/**
 * DeskripsiKompetensiPolicy
 *
 * Mengatur akses untuk tabel "Deskripsi Kompetensi".
 * (Daftar keterangan tentang standar kelulusan atau kemampuan siswa dalam suatu mata pelajaran)
 */
class DeskripsiKompetensiPolicy
{
    /**
     * Bolehkah melihat daftar keseluruhan?
     */
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any DeskripsiKompetensi');
    }

    /**
     * Bolehkah melihat detail salah satu kompetensi?
     */
    public function view(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('view DeskripsiKompetensi');
    }

    /**
     * Bolehkah menambah kompetensi baru?
     */
    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create DeskripsiKompetensi');
    }

    /**
     * Bolehkah mengedit kompetensi yang sudah ada?
     */
    public function update(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('update DeskripsiKompetensi');
    }

    /**
     * Bolehkah menghapus kompetensi?
     */
    public function delete(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('delete DeskripsiKompetensi');
    }

    /**
     * Bolehkah menghapus banyak kompetensi sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any DeskripsiKompetensi');
    }

    /**
     * Bolehkah memulihkan kompetensi yang terhapus?
     */
    public function restore(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('restore DeskripsiKompetensi');
    }

    /**
     * Bolehkah memulihkan banyak kompetensi?
     */
    public function restoreAny(User $user): bool
    {
        return $user->checkPermissionTo('restore-any DeskripsiKompetensi');
    }

    /**
     * Bolehkah menggandakan (duplikat) kompetensi?
     */
    public function replicate(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('replicate DeskripsiKompetensi');
    }

    /**
     * Bolehkah menyusun ulang urutannya?
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder DeskripsiKompetensi');
    }

    /**
     * Bolehkah menghapus kompetensi secara permanen (selamanya)?
     */
    public function forceDelete(User $user, DeskripsiKompetensi $deskripsikompetensi): bool
    {
        return $user->checkPermissionTo('force-delete DeskripsiKompetensi');
    }

    /**
     * Bolehkah menghapus banyak kompetensi secara permanen?
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any DeskripsiKompetensi');
    }
}
