<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Language;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Language::create([
            'mark' => 'en',
            'title' => 'English'
        ]);
        Language::create([
            'mark' => 'hr',
            'title' => 'Hrvatski'
        ]);
        Language::create([
            'mark' => 'it',
            'title' => 'Italiano'
        ]);
        Language::create([
            'mark' => 'de',
            'title' => 'Deutsch'
        ]);
    }
}
