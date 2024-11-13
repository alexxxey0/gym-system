<?php

namespace Database\Seeders;

use App\Models\GroupTraining;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class GroupTrainingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        GroupTraining::factory()->count(20)->create();
    }
}
