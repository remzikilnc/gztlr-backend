<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionsTableSeeder::class)->__invoke();
        app(RolesTableSeeder::class)->__invoke();

        User::factory(15)->create();

        // admin user 1
        $admin = User::create([
            'first_name' => 'remzi',
            'last_name' => 'kılınç',
            'email' => 'remzi@remzi.com',
            'password' => bcrypt('remzi')
        ]);

        $admin->assignRole('admin');


        // default user 1
        $user = User::create([
            'first_name' => 'test',
            'last_name' => 'test last name',
            'email' => 'test@test.com',
            'password' => bcrypt('test')
        ]);

        $user->assignRole('user');
    }
}
