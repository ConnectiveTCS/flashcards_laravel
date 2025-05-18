<?php

namespace Database\Seeders;

use App\Models\Card;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Kyle McPherson',
            'email' => 'kylem.mcpherson@outlook.com',
            'password' => bcrypt('Morgan146@'), // Password is 'password'
        ]);

        // Create 10 cards for the test user
        Card::factory(10)->create([
            'user_id' => 1, // Assuming the test user has ID 1
        ]);


    }
}
