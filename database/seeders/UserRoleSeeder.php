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
        $permissions = config('permissions');

        UserRole::create([
            'title' => 'Admin',
            'name' => 'admin',
            'permissions' => array_values($permissions['EAdminRoles']),
        ]);
        UserRole::create([
            'title' => 'Clinic Owner',
            'name' => 'clinic_owner',
            'permissions' => array_values($permissions['EClinicOwnerRoles']),
        ]);
        UserRole::create([
            'title' => 'Clinic User',
            'name' => 'clinic_user',
            'permissions' => array_values($permissions['EClinicUserRoles']),
        ]);
        UserRole::create([
            'title' => 'User',
            'name' => 'user',
            'permissions' => array_values($permissions['EUserRoles']),
        ]);
    }
}
