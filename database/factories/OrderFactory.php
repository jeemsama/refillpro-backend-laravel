<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
          'user_id'          => 1,
          'shop_id'          => 1,
          'time_slot'        => $this->faker->randomElement(['7AM','11AM','2PM','5PM']),
          'message'          => $this->faker->sentence(),
          'regular_count'    => $this->faker->numberBetween(0,5),
          'dispenser_count'  => $this->faker->numberBetween(0,5),
          'borrow'           => $this->faker->boolean(),
          'swap'             => $this->faker->boolean(),
          'total'            => $this->faker->randomFloat(2, 20, 300),
        ];
    }
}
