<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Service;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $serviceData = [
            [
                'hr' => 'Odaberite područje medicine',
                'en' => 'Choose a medical field'
            ],
            [
                'hr' => 'Anesteziologija',
                'en' => 'Anesthesiology'
            ],
            [
                'hr' => 'Bolnice',
                'en' => 'Hospitals'
            ],
            [
                'hr' => 'Defektologija',
                'en' => 'Defectology'
            ],
            [
                'hr' => 'Dermatovenerologija',
                'en' => 'Dermatology and venereology'
            ],
            [
                'hr' => 'Dijaliza',
                'en' => 'Dialysis'
            ],
            [
                'hr' => 'Domovi za starije i nemoćne',
                'en' => 'Nursing homes'
            ],
            [
                'hr' => 'Estetska kirurgija',
                'en' => 'Aesthetic surgery'
            ],
            [
                'hr' => 'Fizikalna medicina i rehabilitacija',
                'en' => 'Physical medicine and rehabilitation'
            ],
            [
                'hr' => 'Fizikalna terapija',
                'en' => 'Physical therapy'
            ],
            [
                'hr' => 'Gastroenterologija',
                'en' => 'Gastroenterology'
            ],
            [
                'hr' => 'Ginekologija',
                'en' => 'Gynecology'
            ],
            [
                'hr' => 'Haloterapija',
                'en' => 'Halotherapy'
            ],
            [
                'hr' => 'Homeopatija',
                'en' => 'Homeopathy'
            ],
            [
                'hr' => 'Infektologija',
                'en' => 'Infectology'
            ],
            [
                'hr' => 'Interna medicina',
                'en' => 'Internal medicine'
            ],
            [
                'hr' => 'Kardiologija',
                'en' => 'Cardiology'
            ],
            [
                'hr' => 'Kirurgija',
                'en' => 'Surgery'
            ],
            [
                'hr' => 'Laboratorij',
                'en' => 'Laboratory'
            ],
            [
                'hr' => 'Ljekarne',
                'en' => 'Pharmacies'
            ],
            [
                'hr' => 'Medicina rada',
                'en' => 'Occupational medicine'
            ],
            [
                'hr' => 'Medicinska pomagala',
                'en' => 'Medical aids'
            ],
            [
                'hr' => 'Neurologija',
                'en' => 'Neurology'
            ],
            [
                'hr' => 'Nuklearna medicina',
                'en' => 'Nuclear medicine'
            ],
            [
                'hr' => 'Obiteljska medicina',
                'en' => 'Family medicine'
            ],
            [
                'hr' => 'Oftalmologija',
                'en' => 'Ophthalmology'
            ],
            [
                'hr' => 'Onkologija',
                'en' => 'Oncology'
            ],
            [
                'hr' => 'Optika',
                'en' => 'Optics'
            ],
            [
                'hr' => 'Ortodoncija',
                'en' => 'Orthodontics'
            ],
            [
                'hr' => 'Ortopedija',
                'en' => 'Orthopedics'
            ],
            [
                'hr' => 'Otorinolaringologija',
                'en' => 'Otorhinolaryngology'
            ],
            [
                'hr' => 'Pedijatrija',
                'en' => 'Pediatrics'
            ],
            [
                'hr' => 'Poliklinike',
                'en' => 'Polyclinics'
            ],
            [
                'hr' => 'Psihijatrija',
                'en' => 'Psychiatry'
            ],
            [
                'hr' => 'Psihologija',
                'en' => 'Psychology'
            ],
            [
                'hr' => 'Pulmologija',
                'en' => 'Pulmonology'
            ],
            [
                'hr' => 'Radiologija',
                'en' => 'Radiology'
            ],
            [
                'hr' => 'Sanitetski prijevoz',
                'en' => 'Ambulance services'
            ],
            [
                'hr' => 'Stomatologija',
                'en' => 'Dentistry'
            ],
            [
                'hr' => 'Udruge',
                'en' => 'Associations'
            ],
            [
                'hr' => 'Ultrazvučna dijagnostika',
                'en' => 'Ultrasound diagnostics'
            ],
            [
                'hr' => 'Urologija',
                'en' => 'Urology'
            ],
            [
                'hr' => 'Zdravstvena njega u kući',
                'en' => 'Home health care'
            ]
        ];

        foreach ($serviceData as $item) {
            Service::create([
                'name' => $item['hr'],
                'name_en' => $item['en'],
                'name_hr' => $item['hr'],
            ]);
        }
    }
}
