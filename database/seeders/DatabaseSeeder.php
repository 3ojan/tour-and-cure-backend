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

        // clients
        $this->call(UserRoleSeeder::class);
        $this->call(UserSeeder::class);

        // data
        $this->call(LanguageSeeder::class);
        $this->call(MediaSeeder::class);
        $this->call(ClinicSeeder::class);
        $this->call(InquirySeeder::class);
    }
}
