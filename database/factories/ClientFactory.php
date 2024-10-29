<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Client>
 */
class ClientFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    protected $model = Client::class;

    public function definition(): array {
        $memberships = ['All-day', 'Daytime', 'Group training'];
        $memberships_ids = Membership::pluck('membership_id')->toArray();

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
            'membership_id' => $this->faker->randomElement($memberships_ids),
            'membership_until' => $this->faker->dateTimeBetween(now(), now()->addMonth()),
            'role' => 'client'
        ];
    }
}
