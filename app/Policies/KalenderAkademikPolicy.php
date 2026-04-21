<?php

namespace App\Policies;

use App\Models\KalenderAkademik;
use App\Models\User;

class KalenderAkademikPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view-any KalenderAkademik');
    }

    public function view(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        if ($user->hasRole(['Orang Tua', 'Kepala Sekolah', 'Guru'])) {
            return true;
        }
        return $user->checkPermissionTo('view KalenderAkademik');
    }

    public function create(User $user): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('create KalenderAkademik');
    }

    public function update(User $user, KalenderAkademik $kalenderAkademik): bool
    {
        if ($user->hasRole('Orang Tua')) {
            return false;
        }
        return $user->checkPermissionTo('update KalenderAkademik');
    }

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
