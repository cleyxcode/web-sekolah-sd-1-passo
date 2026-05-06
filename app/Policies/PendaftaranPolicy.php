<?php

namespace App\Policies;

use App\Models\Pendaftaran;
use App\Models\User;

/**
 * PendaftaranPolicy
 * 
 * Mengatur hak akses ke menu Pengumuman Pendaftaran (Link Formulir Siswa Baru).
 */
class PendaftaranPolicy
{
    /**
     * Bolehkah melihat daftar informasi pendaftaran?
     */
    public function viewAny(User $user): bool
    {
        // Semua warga sekolah boleh melihat link pendaftaran
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Pendaftaran');
    }

    /**
     * Bolehkah melihat detail link pendaftaran?
     */
    public function view(User $user, Pendaftaran $pendaftaran): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Pendaftaran');
    }

    /**
     * Bolehkah MENAMBAH / MEMBUAT link pendaftaran baru?
     */
    public function create(User $user): bool
    {
        // Hanya Admin Utama yang boleh mengatur (karena krusial)
        return $user->checkPermissionTo('create Pendaftaran');
    }

    /**
     * Bolehkah MENGEDIT link yang sudah ada?
     */
    public function update(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('update Pendaftaran');
    }

    /**
     * Bolehkah MENGHAPUS informasi pendaftaran?
     */
    public function delete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('delete Pendaftaran');
    }

    public function deleteAny(User $user): bool
    {
        return $user->checkPermissionTo('delete-any Pendaftaran');
    }

    public function restore(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('restore Pendaftaran');
    }

    public function forceDelete(User $user, Pendaftaran $pendaftaran): bool
    {
        return $user->checkPermissionTo('force-delete Pendaftaran');
    }
}
