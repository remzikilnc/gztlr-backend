<?php namespace Database\Seeders;

use Database\Default\GetStaticPermissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolesTableSeeder extends Seeder
{
    private Role $role;
    private Permission $permission;

    public function __construct(Role $role, Permission $permission)
    {
        $this->role = $role;
        $this->permission = $permission;
    }

    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $roles = app(GetStaticPermissions::class)->getRoles();

        foreach ($roles as $role) {
            $this->createOrUpdateRole($role);
        }
    }

    private function createOrUpdateRole($role): void
    {
        $defaultPerms = collect($role['permissions'])->map(function ($permission) {
            return is_string($permission) ? ['name' => $permission] : $permission;
        });

        $dbPerms = $this->permission->whereIn('name', $defaultPerms->pluck('name'))->get();

        $dbPerms->map(function (Permission $permission) use ($defaultPerms) {
            return $permission;
        });

        if (Arr::get($role, 'default')) {
            $attributes = ['default' => true];
            $this->role->where('name', $role['name'])->update(['default' => true]);
        } else if (Arr::get($role, 'guests')) {
            $attributes = ['guests' => true];
            $this->role->where('name', $role['name'])->update(['guests' => true]);
        } else {
            $attributes = ['name' => $role['name']];
        }

        $role = $this->role->firstOrCreate($attributes, Arr::except($role, ['permissions']));

        $role->syncPermissions($dbPerms);

        $role->save();
    }
}
