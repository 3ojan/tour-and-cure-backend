<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name' => 'Tihomir',
            'email' => 'tihomir.jauk@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Test12345'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ]);
        User::create([
            'name' => '3ojan',
            'email' => '3ojans@gmail.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Test12345'),
            'remember_token' => Str::random(10),
            'role' => 'admin',
        ]);
        //
        User::create([
            'name' => 'Clinic Owner',
            'email' => 'clinic_owner@tourcure.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Test1234'),
            'remember_token' => Str::random(10),
            'role' => 'clinic_owner',
        ]);
        User::create([
            'name' => 'Clinic User',
            'email' => 'clinic_user@tourcure.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Test1234'),
            'remember_token' => Str::random(10),
            'role' => 'clinic_user',
        ]);
        User::create([
            'name' => 'Test User',
            'email' => 'user@tourcure.com',
            'email_verified_at' => now(),
            'password' => Hash::make('Test1234'),
            'remember_token' => Str::random(10),
            'role' => 'user',
        ]);
        User::factory(10)->create();
    }
}
