<?php

namespace App\Policies;

use App\Models\User;
use App\Models\CatatanPerkembangan;

class CatatanPerkembanganPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    public function view(User $user, CatatanPerkembangan $catatanPerkembangan): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function update(User $user, CatatanPerkembangan $catatanPerkembangan): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function delete(User $user, CatatanPerkembangan $catatanPerkembangan): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }
}
