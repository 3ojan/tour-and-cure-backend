<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

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
        $this->call(ServiceTypeSeeder::class);
        $this->call(CategorySeeder::class);
        $this->call(MediaSeeder::class);
        $this->call(ClinicSeeder::class);
        $this->call(InquirySeeder::class);
    }
}
