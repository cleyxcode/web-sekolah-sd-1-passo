<?php

namespace App\Policies;

use App\Models\KalenderAkademik;
use App\Models\User;

/**
 * KalenderAkademikPolicy
 *
 * Mengatur hak akses fitur Kalender Akademik (pengumuman hari libur, ujian, acara sekolah).
 */
class KalenderAkademikPolicy
{
    /**
     * Siapa yang boleh melihat daftar acara kalender?
     */
    public function viewAny(User $user): bool
    {
        // Semua warga sekolah (Orang Tua, Guru, Kepsek) boleh melihat jadwal
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }

        return $user->checkPermissionTo('view-any KalenderAkademik');
    }

    /**
     * Siapa yang boleh melihat detail acara di kalender?
     */
    public function view(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }

        return $user->checkPermissionTo('view KalenderAkademik');
    }

    /**
     * Siapa yang boleh MENAMBAH acara baru di kalender?
     */
    public function create(User $user): bool
    {
        // Orang Tua DILARANG
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('create KalenderAkademik');
    }

    /**
     * Siapa yang boleh MENGEDIT jadwal acara?
     */
    public function update(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('update KalenderAkademik');
    }

    /**
     * Siapa yang boleh MENGHAPUS acara dari kalender?
     */
    public function delete(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('delete KalenderAkademik');
    }

    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }

        return $user->checkPermissionTo('delete-any KalenderAkademik');
    }

    public function restore(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        return $user->checkPermissionTo('restore KalenderAkademik');
    }

    public function forceDelete(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        return $user->checkPermissionTo('force-delete KalenderAkademik');
    }
}
