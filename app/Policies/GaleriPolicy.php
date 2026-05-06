<?php

namespace App\Policies;

use App\Models\Galeri;
use App\Models\User;

/**
 * GaleriPolicy
 * 
 * Mengatur hak akses foto-foto dan video dokumentasi (Galeri) sekolah.
 */
class GaleriPolicy
{
    /**
     * Siapa yang boleh melihat halaman DAFTAR foto/video galeri?
     */
    public function viewAny(User $user): bool
    {
        // Orang Tua, Kepala Sekolah, dan Guru otomatis boleh melihat galeri
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any Galeri');
    }

    /**
     * Siapa yang boleh melihat satu foto/video tertentu?
     */
    public function view(User $user, Galeri $galeri): bool
    {
        // Sama seperti viewAny, ketiga role ini selalu diizinkan
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view Galeri');
    }

    /**
     * Siapa yang boleh MENGUNGGAH / MEMBUAT galeri baru?
     */
    public function create(User $user): bool
    {
        // Orang Tua DILARANG mengunggah ke galeri sekolah
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create Galeri');
    }

    /**
     * Siapa yang boleh MENGEDIT info galeri (mengubah judul foto dsb)?
     */
    public function update(User $user, Galeri $galeri): bool
    {
        // Orang Tua DILARANG
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update Galeri');
    }

    /**
     * Siapa yang boleh MENGHAPUS foto/video di galeri?
     */
    public function delete(User $user, Galeri $galeri): bool
    {
        // Orang Tua DILARANG
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete Galeri');
    }

    /**
     * Siapa yang boleh menghapus banyak galeri sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('delete-any Galeri');
    }

    /**
     * Siapa yang boleh mengembalikan foto/video yang tak sengaja terhapus?
     */
    public function restore(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('restore Galeri');
    }

    /**
     * Siapa yang boleh menghapus foto/video secara permanen (selamanya)?
     */
    public function forceDelete(User $user, Galeri $galeri): bool
    {
        return $user->checkPermissionTo('force-delete Galeri');
    }
}
