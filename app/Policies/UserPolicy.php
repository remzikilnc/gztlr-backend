<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function view(User $user): bool
    {
        return $user->hasPermissionTo('users.viewAny');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function show(User $user, User $model): bool
    {
        return $user->hasPermissionTo('users.view') || $user->id === $model->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->hasPermissionTo('users.create');
    }

    /**
     * Determine whether the user can update the target user.
     */
    public function update(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('users.update.all')) {
            return true;
        }

        if ($user->id === $model->id) {
            return true;
        }

        foreach ($model->roles as $role) {
            $permission = 'users.update.' . strtolower($role->name);
            if ($user->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can update users email verified time.
     */
    public function updateEmailVerifiedAt(User $user): bool
    {
        return $user->hasPermissionTo('users.update.emailverified');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, User $model): bool
    {
        if ($user->hasPermissionTo('users.delete.all')) {
            return true;
        }

        if ($user->id === $model->id) {
            return true;
        }

        foreach ($model->roles as $role) {
            $permission = 'users.delete.' . strtolower($role->name);
            if ($user->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can assign role to the users.
     */
    public function assignRole(User $currentUser,User $model, string $roleName): bool
    {
        if ($currentUser->hasPermissionTo('roles.assign.all')) {
            return true;
        }

        return $currentUser->hasPermissionTo("roles.assign.{$roleName}");
    }

    /**
     * Determine whether the user can update users status.
     */
    public function suspend(User $user, User $model){
        if ($user->hasPermissionTo('users.suspend.all')) {
            return true;
        }

        foreach ($model->roles as $role) {
            $permission = 'users.suspend.' . strtolower($role->name);
            if ($user->hasPermissionTo($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine whether the user can see users statistics.
     */
    public function statistics(User $currentUser){
        if ($currentUser->hasPermissionTo('users.statistics')) {
            return true;
        }

        return false;
    }
}
