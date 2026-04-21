<?php

namespace App\Policies;

use Spatie\Permission\Models\Role;
use App\Models\User;

class RolePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->checkPermissionTo('view-any Role');
    }

    public function view(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('view Role');
    }

    public function create(User $user): bool
    {
        return $user->checkPermissionTo('create Role');
    }

    public function update(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('update Role');
    }

    public function delete(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('delete Role');
    }

    public function restore(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('restore Role');
    }

    public function forceDelete(User $user, Role $role): bool
    {
        return $user->checkPermissionTo('force-delete Role');
    }
}
