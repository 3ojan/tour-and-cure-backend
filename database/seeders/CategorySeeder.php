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
                'code' => 'AESTHETIC_SURGERY',
                'hr' => 'Estetska kirurgija',
                'en' => 'Aesthetic surgery'
            ],
            [
                'code' => 'AMBULANCE_SERVICES',
                'hr' => 'Sanitetski prijevoz',
                'en' => 'Ambulance services'
            ],
            [
                'code' => 'ANESTHESIOLOGY',
                'hr' => 'Anesteziologija',
                'en' => 'Anesthesiology'
            ],
            [
                'code' => 'ASSOCIATIONS',
                'hr' => 'Udruge',
                'en' => 'Associations'
            ],
            [
                'code' => 'CARDIOLOGY',
                'hr' => 'Kardiologija',
                'en' => 'Cardiology'
            ],
            [
                'code' => 'DEFECTOLOGY',
                'hr' => 'Defektologija',
                'en' => 'Defectology'
            ],
            [
                'code' => 'DENTISTRY',
                'hr' => 'Stomatologija',
                'en' => 'Dentistry'
            ],
            [
                'code' => 'DERMATOLOGY_AND_VENEREOLOGY',
                'hr' => 'Dermatovenerologija',
                'en' => 'Dermatology and venereology'
            ],
            [
                'code' => 'DIALYSIS',
                'hr' => 'Dijaliza',
                'en' => 'Dialysis'
            ],
            [
                'code' => 'FAMILY_MEDICINE',
                'hr' => 'Obiteljska medicina',
                'en' => 'Family medicine'
            ],
            [
                'code' => 'GASTROENTEROLOGY',
                'hr' => 'Gastroenterologija',
                'en' => 'Gastroenterology'
            ],
            [
                'code' => 'GYNECOLOGY',
                'hr' => 'Ginekologija',
                'en' => 'Gynecology'
            ],
            [
                'code' => 'HALOTHERAPY',
                'hr' => 'Haloterapija',
                'en' => 'Halotherapy'
            ],
            [
                'code' => 'HOME_HEALTH_CARE',
                'hr' => 'Zdravstvena njega u kući',
                'en' => 'Home health care'
            ],
            [
                'code' => 'HOMEOPATHY',
                'hr' => 'Homeopatija',
                'en' => 'Homeopathy'
            ],
            [
                'code' => 'HOSPITALS',
                'hr' => 'Bolnice',
                'en' => 'Hospitals'
            ],
            [
                'code' => 'INFECTOLOGY',
                'hr' => 'Infektologija',
                'en' => 'Infectology'
            ],
            [
                'code' => 'INTERNAL_MEDICINE',
                'hr' => 'Interna medicina',
                'en' => 'Internal medicine'
            ],
            [
                'code' => 'LABORATORY',
                'hr' => 'Laboratorij',
                'en' => 'Laboratory'
            ],
            [
                'code' => 'MEDICAL_AIDS',
                'hr' => 'Medicinska pomagala',
                'en' => 'Medical aids'
            ],
            [
                'code' => 'NEUROLOGY',
                'hr' => 'Neurologija',
                'en' => 'Neurology'
            ],
            [
                'code' => 'NUCLEAR_MEDICINE',
                'hr' => 'Nuklearna medicina',
                'en' => 'Nuclear medicine'
            ],
            [
                'code' => 'OCCUPATIONAL_MEDICINE',
                'hr' => 'Medicina rada',
                'en' => 'Occupational medicine'
            ],
            [
                'code' => 'ONCOLOGY',
                'hr' => 'Onkologija',
                'en' => 'Oncology'
            ],
            [
                'code' => 'OPHTHALMOLOGY',
                'hr' => 'Oftalmologija',
                'en' => 'Ophthalmology'
            ],
            [
                'code' => 'OPTICS',
                'hr' => 'Optika',
                'en' => 'Optics'
            ],
            [
                'code' => 'ORTHODONTICS',
                'hr' => 'Ortodoncija',
                'en' => 'Orthodontics'
            ],
            [
                'code' => 'ORTHOPEDICS',
                'hr' => 'Ortopedija',
                'en' => 'Orthopedics'
            ],
            [
                'code' => 'OTORHINOLARYNGOLOGY',
                'hr' => 'Otorinolaringologija',
                'en' => 'Otorhinolaryngology'
            ],
            [
                'code' => 'PEDIATRICS',
                'hr' => 'Pedijatrija',
                'en' => 'Pediatrics'
            ],
            [
                'code' => 'PHARMACIES',
                'hr' => 'Ljekarne',
                'en' => 'Pharmacies'
            ],
            [
                'code' => 'PHYSICAL_MEDICINE_AND_REHABILITATION',
                'hr' => 'Fizikalna medicina i rehabilitacija',
                'en' => 'Physical medicine and rehabilitation'
            ],
            [
                'code' => 'PHYSICAL_THERAPY',
                'hr' => 'Fizikalna terapija',
                'en' => 'Physical therapy'
            ],
            [
                'code' => 'POLYCLINICS',
                'hr' => 'Poliklinike',
                'en' => 'Polyclinics'
            ],
            [
                'code' => 'PSYCHIATRY',
                'hr' => 'Psihijatrija',
                'en' => 'Psychiatry'
            ],
            [
                'code' => 'PSYCHOLOGY',
                'hr' => 'Psihologija',
                'en' => 'Psychology'
            ],
            [
                'code' => 'PULMONOLOGY',
                'hr' => 'Pulmologija',
                'en' => 'Pulmonology'
            ],
            [
                'code' => 'RADIOLOGY',
                'hr' => 'Radiologija',
                'en' => 'Radiology'
            ],
            [
                'code' => 'SURGERY',
                'hr' => 'Kirurgija',
                'en' => 'Surgery'
            ],
            [
                'code' => 'ULTRASOUND_DIAGNOSTICS',
                'hr' => 'Ultrazvučna dijagnostika',
                'en' => 'Ultrasound diagnostics'
            ],
            [
                'code' => 'UROLOGY',
                'hr' => 'Urologija',
                'en' => 'Urology'
            ],
            [
                'code' => 'NURSING_HOMES',
                'hr' => 'Domovi za starije i nemoćne',
                'en' => 'Nursing homes'
            ],
        ];

        foreach ($categories as $category) {
            Category::create([
                'code' => $category['code'],
                'en' => $category['en'],
                'hr' => $category['hr'],
            ]);
        }
    }
}
