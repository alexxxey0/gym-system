<?php

namespace Database\Seeders;

use App\Models\Gym;
use Illuminate\Database\Seeder;
use Database\Factories\GymFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GymSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $gyms = ['Teika', 'Purvciems', 'Mežciems', 'Jugla', 'Pļavnieki'];

        foreach ($gyms as $gym) {
            Gym::factory()->create([
                'name' => $gym
            ]);
        }
    }
}
