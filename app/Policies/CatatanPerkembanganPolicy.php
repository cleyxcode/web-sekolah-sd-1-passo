<?php

namespace App\Policies;

use App\Models\CatatanPerkembangan;
use App\Models\User;

/**
 * CatatanPerkembanganPolicy
 *
 * Mengatur hak akses untuk catatan yang ditulis oleh wali kelas tentang perkembangan siswanya.
 */
class CatatanPerkembanganPolicy
{
    /**
     * Siapa yang boleh melihat halaman DAFTAR catatan perkembangan?
     */
    public function viewAny(User $user): bool
    {
        // Diizinkan untuk Admin, Kepsek, Guru, dan Orang Tua
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    /**
     * Siapa yang boleh membaca ISI/DETAIL catatan perkembangan?
     */
    public function view(User $user, CatatanPerkembangan $catatanPerkembangan): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    /**
     * Siapa yang boleh MEMBUAT catatan perkembangan baru?
     */
    public function create(User $user): bool
    {
        // Hanya Admin dan Guru (Wali Kelas) yang boleh menulis catatan baru
        // Kepsek dan Orang Tua cuma boleh baca, tidak boleh nulis.
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Siapa yang boleh MENGUBAH / MENGEDIT catatan yang sudah ada?
     */
    public function update(User $user, CatatanPerkembangan $catatanPerkembangan): bool
    {
        // Hanya Admin dan Guru
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Siapa yang boleh MENGHAPUS catatan?
     */
    public function delete(User $user, CatatanPerkembangan $catatanPerkembangan): bool
    {
        // Hanya Admin dan Guru
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }
}
