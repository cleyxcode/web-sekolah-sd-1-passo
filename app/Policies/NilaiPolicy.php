<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;

/**
 * NilaiPolicy
 *
 * Mengatur keamanan dan hak akses untuk data Nilai Ujian Siswa (E-Rapor).
 */
class NilaiPolicy
{
    /**
     * Siapa yang boleh melihat daftar halaman Nilai?
     */
    public function viewAny(User $user): bool
    {
        // Semua warga sekolah diizinkan membuka menu nilai
        // (Catatan: Walau bisa buka menu, data yang tampil sudah disaring di dalam NilaiResource)
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    /**
     * Siapa yang boleh melihat detail baris nilai tertentu?
     */
    public function view(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    /**
     * Siapa yang boleh MEMASUKKAN / MENGINPUT nilai baru?
     */
    public function create(User $user): bool
    {
        // Hanya Admin dan Guru pengajar yang boleh menginput nilai
        // Kepsek dan Orang Tua cuma bisa melihat (view)
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Siapa yang boleh MENGEDIT nilai yang sudah ada?
     */
    public function update(User $user, Nilai $nilai): bool
    {
        // Hanya Admin dan Guru
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Siapa yang boleh MENGHAPUS nilai siswa?
     */
    public function delete(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Siapa yang boleh MENGHAPUS BANYAK nilai sekaligus?
     */
    public function deleteAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    /**
     * Siapa yang boleh memulihkan nilai yang terhapus sementara?
     */
    public function restore(User $user, Nilai $nilai): bool
    {
        // Khusus kembalikan data, hanya Admin utama yang boleh
        return $user->hasRole('Super Admin');
    }

    /**
     * Siapa yang boleh menghapus nilai secara PERMANEN (tak bisa kembali)?
     */
    public function forceDelete(User $user, Nilai $nilai): bool
    {
        // Sama, hanya Admin yang boleh melakukan aksi fatal ini
        return $user->hasRole('Super Admin');
    }
}
