<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Coach>
 */
class CoachFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        return [
            'name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'personal_id' => sprintf(
                '%06d-%05d',
                $this->faker->unique()->numberBetween(0, 999999),
                $this->faker->unique()->numberBetween(0, 99999)
            ),
            'password' => bcrypt($this->faker->password),
            'phone' => $this->faker->unique()->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'role' => 'coach'
        ];
    }
}
