<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Membership;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory {
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array {
        $clients_ids = Client::pluck('client_id')->toArray();
        $payment_methods = ['card', 'cash', 'Stripe'];
        $payment_purposes = ['Buying membership', 'Extending membership', 'Upgrading membership'];
        $memberships_ids = Membership::pluck('membership_id')->toArray();

        return [
            'client_id' => $this->faker->randomElement($clients_ids),
            'payment_method' => $this->faker->randomElement($payment_methods),
            'payment_purpose' => $this->faker->randomElement($payment_purposes),
            'membership_id' => $this->faker->randomElement($memberships_ids),
            'payment_status' => 'COMPLETED',
            'amount' => $this->faker->randomFloat(2, 5, 30),
            'created_at' => $this->faker->dateTimeBetween('-3 years', 'now'),
            'completed_at' => $this->faker->dateTimeBetween('-3 years', 'now')
        ];
    }
}
