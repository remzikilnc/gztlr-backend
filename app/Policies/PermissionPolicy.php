<?php

namespace App\Policies;

use App\Models\User;

class PermissionPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasPermissionTo('permission.viewAny');
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('permission.view');
    }

    public function update(User $user): bool
    {
        return $user->hasPermissionTo('permission.update');

    }

}
