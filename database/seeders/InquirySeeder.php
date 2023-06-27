<?php

namespace Database\Seeders;

use App\Models\Inquiry;
use Illuminate\Database\Seeder;

class InquirySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Inquiry::create([
            'user_id' => 1,
            'service_type_id' => 1,
            'form_json' => [
                'subject' => 'Ja bi nekaj',
                'body' => 'Bok, ja bi nekaj a ne znam kaj, jel bi i vi?',
                'extras' => [
                    'key' => 'value'
                ]
            ]
        ]);
    }
}
