<?php namespace Database\Seeders;

use Database\Default\GetStaticPermissions;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $permissions = app(GetStaticPermissions::class)->getPermissions();

        foreach ($permissions as $groupName => $group) {
            foreach ($group as $permission) {
                if (!str_contains($permission['name'], "{$groupName}.")) {
                    $permission['name'] = $groupName . '.' . $permission['name'];
                }

                $permission['group'] = $groupName;
                app(Permission::class)->updateOrCreate(['name' => $permission['name'], 'guard_name' => $permission['guard_name'] ?? 'api'], $permission);
            }
        }
        app(Permission::class)->whereNull('group')->delete();
    }
}
