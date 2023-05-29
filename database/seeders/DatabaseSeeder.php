<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // data
        $this->call(CountrySeeder::class);
        $this->call(CurrencySeeder::class);

        // install passport
        // Artisan::call('passport:install');

        // clients
        $this->call(UserRoleSeeder::class);
        $this->call(UserSeeder::class);
    }
}
