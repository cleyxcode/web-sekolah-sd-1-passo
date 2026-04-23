<?php

namespace App\Policies;

use App\Models\Presensi;
use App\Models\User;

class PresensiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    public function view(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function update(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function delete(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function restore(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function forceDelete(User $user, Presensi $presensi): bool
    {
        return $user->hasRole('Super Admin');
    }
}
