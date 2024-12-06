<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\GroupTraining;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Attendance>
 */
class AttendanceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $clients_ids = Client::pluck('client_id')->toArray();
        $trainings_ids = GroupTraining::pluck('training_id')->toArray();

        return [
            'client_id' => $this->faker->randomElement($clients_ids),
            'training_id' => $this->faker->randomElement($trainings_ids),
            'training_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'attended' => $this->faker->randomElement([true, false])
        ];
    }
}
