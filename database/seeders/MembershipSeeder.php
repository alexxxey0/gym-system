<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Membership;

class MembershipSeeder extends Seeder {
    /**
     * Run the database seeds.
     */
    public function run(): void {
        $memberships = [
            [
                'membership_name' => 'Daytime',
                'price' => 19.99,
                'group_trainings_included' => false,
                'entry_from_workdays' => '08:00:00',
                'entry_until_workdays' => '17:00:00',
                'entry_from_weekends' => '09:00:00',
                'entry_until_weekends' => '20:00:00'
            ],
            [
                'membership_name' => 'All-day',
                'price' => 24.99,
                'group_trainings_included' => false,
                'entry_from_workdays' => '08:00:00',
                'entry_until_workdays' => '22:00:00',
                'entry_from_weekends' => '09:00:00',
                'entry_until_weekends' => '20:00:00'
            ],
            [
                'membership_name' => 'Group training',
                'price' => 29.99,
                'group_trainings_included' => true,
                'entry_from_workdays' => '08:00:00',
                'entry_until_workdays' => '22:00:00',
                'entry_from_weekends' => '09:00:00',
                'entry_until_weekends' => '20:00:00'
            ]
        ];

        foreach ($memberships as $membership) {
            Membership::create($membership);
        }
    }
}
