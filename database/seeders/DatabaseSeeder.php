<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        $this->call([
            AdminSeeder::class,
            MembershipSeeder::class,
            ClientSeeder::class,
            CoachSeeder::class,
            GroupTrainingSeeder::class,
            PaymentSeeder::class,
            AttendanceSeeder::class
        ]);
    }
}
