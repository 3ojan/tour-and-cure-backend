<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Message;
use Faker\Factory as Faker;

class MessageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $faker = Faker::create();

        // Get all users
        $users = User::all();

        // Seed messages
        foreach ($users as $user) {
            // Generate random number of messages between 50 and 100 per user
            $messageCount = rand(50, 100);

            for ($i = 0; $i < $messageCount; $i++) {
                // Get a random user (excluding the current user)
                $randomUser = $users->except($user->id)->random();

                // Create a message
                Message::create([
                    'sender_id' => $user->id,
                    'receiver_id' => $randomUser->id,
                    'message' => $faker->sentence,
                ]);
            }
        }
    }
}
