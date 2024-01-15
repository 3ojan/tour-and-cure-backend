<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'code' => 'DENTISTRY',
                'hr' => 'Stomatologija',
                'en' => 'Dentistry'
            ],
                [
                    'code' => 'ORTHODONTICS',
                    'hr' => 'Ortodoncija',
                    'en' => 'Orthodontics',
                    'parent_id' => 'DENTISTRY'
                ],


            [
                'code' => 'ORTHOPEDICS',
                'hr' => 'Ortopedija',
                'en' => 'Orthopedics'
            ],


            [
                'code' => 'AESTHETIC_SURGERY',
                'hr' => 'Estetska kirurgija',
                'en' => 'Aesthetic surgery'
            ],
                [
                    'code' => 'BARIATRIC_SURGERY',
                    'hr' => 'Barijatrijska kirurgija',
                    'en' => 'Bariatric surgery',
                    'parent_id' => 'AESTHETIC_SURGERY'
                ],


            [
                'code' => 'SURGERY',
                'hr' => 'Kirurgija',
                'en' => 'Surgery'
            ],


            [
                'code' => 'OPHTHALMOLOGY',
                'hr' => 'Oftalmologija',
                'en' => 'Ophthalmology'
            ],
                [
                    'code' => 'CORRECTIONS',
                    'hr' => 'Korekcija vida',
                    'en' => 'Vision correction',
                    'parent_id' => 'OPHTHALMOLOGY'
                ],
                [
                    'code' => 'OPTICS',
                    'hr' => 'Optika',
                    'en' => 'Optics',
                    'parent_id' => 'OPHTHALMOLOGY'
                ],


            [
                'code' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY',
                'hr' => 'Diagnostika i ne invazivni zahvati',
                'en' => 'Diagnostics and non invasive surgery'
            ],
                [
                    'code' => 'LABORATORY',
                    'hr' => 'Laboratorij',
                    'en' => 'Laboratory',
                    'parent_id' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY'

                ],
                [
                    'code' => 'NUCLEAR_MEDICINE',
                    'hr' => 'Nuklearna medicina',
                    'en' => 'Nuclear medicine',
                    'parent_id' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY'

                ],
                [
                    'code' => 'RADIOLOGY',
                    'hr' => 'Radiologija',
                    'en' => 'Radiology',
                    'parent_id' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY'
                ],

                [
                    'code' => 'ULTRASOUND_DIAGNOSTICS',
                    'hr' => 'Ultrazvučna dijagnostika',
                    'en' => 'Ultrasound diagnostics',
                    'parent_id' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY'
                ],
                [
                    'code' => 'CARDIOLOGY',
                    'hr' => 'Kardiologija',
                    'en' => 'Cardiology',
                    'parent_id' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY'
                ],
                [
                    'code' => 'OTORHINOLARYNGOLOGY',
                    'hr' => 'Otorinolaringologija',
                    'en' => 'Otorhinolaryngology',
                    'parent_id' => 'DIAGNOSTICS_AND_NON_INVASIVE_SURGERY'
                ],


            [
                'code' => 'FERTILITY',
                'hr' => 'Plodnost',
                'en' => 'Fertility'
            ],
                [
                    'code' => 'GYNECOLOGY',
                    'hr' => 'Ginekologija',
                    'en' => 'Gynecology',
                    'parent_id' => 'FERTILITY'
                ],
                [
                    'code' => 'PEDIATRICS',
                    'hr' => 'Pedijatrija',
                    'en' => 'Pediatrics',
                    'parent_id' => 'FERTILITY'
                ],


            [
                'code' => 'PHYSICAL_MEDICINE_AND_REHABILITATION',
                'hr' => 'Fizikalna medicina i rehabilitacija',
                'en' => 'Physical medicine and rehabilitation'
            ],
                [
                    'code' => 'PHYSICAL_THERAPY',
                    'hr' => 'Fizikalna terapija',
                    'en' => 'Physical therapy',
                    'parent_id' => 'PHYSICAL_MEDICINE_AND_REHABILITATION'
                ],
        ];

        foreach ($categories as $category) {
            if (array_key_exists('parent_id', $category)) {
                $parent_id = \App\Models\Category::where('code', $category['parent_id'])->value('id');

                Category::create([
                    'code' => $category['code'],
                    'en' => $category['en'],
                    'hr' => $category['hr'],
                    'parent_id' => $parent_id
                ]);
            } else {
                Category::create([
                    'code' => $category['code'],
                    'en' => $category['en'],
                    'hr' => $category['hr'],
                ]);
            }
        }
    }
}
