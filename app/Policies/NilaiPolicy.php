<?php

namespace App\Policies;

use App\Models\Nilai;
use App\Models\User;

class NilaiPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    public function view(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Kepala Sekolah') || $user->hasRole('Guru') || $user->hasRole('Orang Tua');
    }

    public function create(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function update(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function delete(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function deleteAny(User $user): bool
    {
        return $user->hasRole('Super Admin') || $user->hasRole('Guru');
    }

    public function restore(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin');
    }

    public function forceDelete(User $user, Nilai $nilai): bool
    {
        return $user->hasRole('Super Admin');
    }
}
