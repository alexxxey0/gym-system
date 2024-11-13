<?php

namespace Database\Factories;

use App\Models\Coach;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\GroupTraining>
 */
class GroupTrainingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Random schedules
        $schedules = [
            ["monday" => ["start" => "09:30", "end" => "10:45"], "tuesday" => ["start" => "13:15", "end" => "14:45"], "thursday" => ["start" => "15:00", "end" => "16:30"]],
            ["wednesday" => ["start" => "08:00", "end" => "08:45"], "friday" => ["start" => "11:30", "end" => "13:00"]],
            ["tuesday" => ["start" => "10:15", "end" => "11:15"], "friday" => ["start" => "14:30", "end" => "15:30"]],
            ["monday" => ["start" => "12:00", "end" => "13:30"], "wednesday" => ["start" => "15:45", "end" => "17:15"]],
            ["tuesday" => ["start" => "09:00", "end" => "10:30"], "thursday" => ["start" => "13:30", "end" => "15:00"], "saturday" => ["start" => "16:15", "end" => "17:30"]],
            ["monday" => ["start" => "07:30", "end" => "08:30"], "friday" => ["start" => "18:00", "end" => "19:30"]],
            ["tuesday" => ["start" => "08:45", "end" => "09:30"], "thursday" => ["start" => "14:00", "end" => "15:30"]],
            ["wednesday" => ["start" => "09:15", "end" => "10:45"], "friday" => ["start" => "12:30", "end" => "13:45"], "saturday" => ["start" => "16:00", "end" => "17:30"]],
            ["monday" => ["start" => "10:00", "end" => "11:30"], "wednesday" => ["start" => "13:45", "end" => "15:15"], "thursday" => ["start" => "17:00", "end" => "18:30"]],
            ["tuesday" => ["start" => "07:45", "end" => "08:45"], "thursday" => ["start" => "11:00", "end" => "12:30"]],
        ];

        $coaches_ids = Coach::pluck('coach_id')->toArray();
        

        return [
            'name' => $this->faker->word(),
            'description' => $this->faker->text(400),
            'coach_id' => $this->faker->randomElement($coaches_ids),
            'schedule' => json_encode($this->faker->randomElement($schedules)),
            'clients_signed_up' => 0,
            'max_clients' => $this->faker->numberBetween(10, 50),
            'path_to_image' => null,
            'active' => true
        ];
    }
}
