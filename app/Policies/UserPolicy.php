<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Http\Request;

class UserPolicy
{
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function view(User $user): bool
    {
        return $user->hasPermissionTo('users.view');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.show') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users.create');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('users.update')) {
            return true;
        }

        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }

    public function updateEmailVerifiedAt(User $user): bool
    {
        return $user->hasPermissionTo('users.update');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('users.delete')) {
            return true;
        }

        // user is deleting own model
        if ($user->id === $model->id) {
            return true;
        }

        return false;
    }

    public function assignRole(User $currentUser, string $roleName): bool
    {
        if ($currentUser->hasPermissionTo('roles.assign.all')) {
            return true;
        }

        return $currentUser->hasPermissionTo("roles.assign.{$roleName}.");
    }
}
