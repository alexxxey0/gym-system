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
        $gyms = [
            [
                "name" => "Teika",
                "description" => "Moderns sporta klubs ar plašu trenažieru izvēli un grupu nodarbībām.",
                "address" => "Brīvības gatve 200, Rīga, LV-1039"
            ],
            [
                "name" => "Purvciems",
                "description" => "Draudzīga atmosfēra un profesionāli treneri, kas palīdzēs sasniegt mērķus.",
                "address" => "Daugavas iela 5, Rīga, LV-1082"
            ],
            [
                "name" => "Mežciems",
                "description" => "Plašas telpas ar jaunākajām fitnesa tehnoloģijām un SPA zonu.",
                "address" => "Malienas iela 2, Rīga, LV-1079"
            ],
            [
                "name" => "Pļavnieki",
                "description" => "Sporta klubs ar dažādām nodarbībām un bērnu aktivitāšu zonu.",
                "address" => "Saharova iela 20, Rīga, LV-1083"
            ],
            [
                "name" => "Jugla",
                "description" => "Ērta lokācija ar plašu fitnesa piedāvājumu un uztura konsultācijām.",
                "address" => "Juglas iela 15, Rīga, LV-1024"
            ]
        ];

        foreach ($gyms as $gym) {
            Gym::factory()->create([
                'name' => $gym['name'],
                'description' => $gym['description'],
                'address' => $gym['address']
            ]);
        }
    }
}
