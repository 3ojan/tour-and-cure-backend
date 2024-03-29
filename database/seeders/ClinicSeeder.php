<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

use App\Models\Clinic;
use App\Models\ServiceType;

class ClinicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Clinic::create([
            'name' => "Poliklinika 3ojan",
            'description' => "Where all your dreams come true",
            'address' => "Ilica 1234BB",
            'postcode' => "10000",
            'city' => "Zagreb",
            'country_id' => 56,

            'latitude' => "46.1",
            'longitude' => "16.1",

            'web' => fake()->url(),
            'email' => fake()->unique()->safeEmail(),
            'mobile' => fake()->phoneNumber(),
            'phone' => fake()->phoneNumber(),

            'contact_person' => fake()->name(),
            'contact_email' => fake()->unique()->safeEmail(),
            'contact_phone' => fake()->phoneNumber(),
        ]);

        Clinic::factory(5)->create();

        $categories = Category::all();

        Clinic::all()->each(function ($clinic) use ($categories) {
            $clinic->categories()->attach(
                $categories->random(rand(1, 10))->pluck('id')->toArray()
            );
        });
    }
}
