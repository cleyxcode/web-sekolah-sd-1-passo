<?php

namespace App\Policies;

use App\Models\Berita;
use App\Models\User;

/**
 * BeritaPolicy
 * 
 * Kebijakan hak akses untuk tabel Berita.
 * Mengatur siapa saja yang boleh membuat, mengedit, atau menghapus berita di website.
 */
class BeritaPolicy
{
    /**
     * Menentukan siapa yang boleh melihat DAFTAR berita.
     */
    public function viewAny(User $user): bool
    {
        // Semua role (Orang Tua, Kepsek, Guru) diizinkan melihat daftar berita
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        // Jika bukan ketiga role di atas, cek izin khusus miliknya
        return $user->checkPermissionTo('view-any Berita');
    }

    /**
     * Menentukan siapa yang boleh melihat ISI/DETAIL dari satu berita.
     */
    public function view(User $user, Berita $berita): bool
    {
        // Sama seperti melihat daftar, semua role ini diizinkan
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Berita');
    }

    /**
     * Menentukan siapa yang boleh MEMBUAT berita baru.
     */
    public function create(User $user): bool
    {
        // Orang Tua sama sekali DILARANG membuat berita
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Berita');
    }

    /**
     * Menentukan siapa yang boleh MENGEDIT berita yang sudah ada.
     */
    public function update(User $user, Berita $berita): bool
    {
        // Orang Tua DILARANG mengedit berita
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Berita');
    }

    /**
     * Menentukan siapa yang boleh MENGHAPUS satu berita.
     */
    public function delete(User $user, Berita $berita): bool
    {
        // Orang Tua DILARANG menghapus berita
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Berita');
    }

    /**
     * Menentukan siapa yang boleh MENGHAPUS BANYAK berita sekaligus.
     */
    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Berita');
    }

    /**
     * Menentukan siapa yang boleh mengembalikan berita yang terhapus (restore).
     */
    public function restore(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('restore Berita');
    }

    /**
     * Menentukan siapa yang boleh menggeser/mengubah urutan berita.
     */
    public function reorder(User $user): bool
    {
        return $user->checkPermissionTo('reorder Berita');
    }

    /**
     * Menentukan siapa yang boleh MENGHAPUS PERMANEN satu berita.
     */
    public function forceDelete(User $user, Berita $berita): bool
    {
        return $user->checkPermissionTo('force-delete Berita');
    }

    /**
     * Menentukan siapa yang boleh MENGHAPUS PERMANEN banyak berita.
     */
    public function forceDeleteAny(User $user): bool
    {
        return $user->checkPermissionTo('force-delete-any Berita');
    }
}
