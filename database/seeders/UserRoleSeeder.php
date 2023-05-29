<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\UserRole;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        UserRole::create([
            'title' => 'Administrator',
            'name' => 'admin',
            'permissions' => ['admin'],
        ]);
        UserRole::create([
            'title' => 'Client',
            'name' => 'client',
            'permissions' => ['change_own_password'],
        ]);
        UserRole::create([
            'title' => 'User',
            'name' => 'user',
            'permissions' => ['change_own_password'],
        ]);
    }
}
