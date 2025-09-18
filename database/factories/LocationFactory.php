<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Location>
 */
class LocationFactory extends Factory
{
        /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'location' => $this->faker->company() . ' Office',
            'latitude' => $this->faker->latitude(-8, -5), // Indonesia latitude range
            'longitude' => $this->faker->longitude(95, 141), // Indonesia longitude range
            'description' => $this->faker->sentence(),
            'address' => $this->faker->address(),
            'status' => $this->faker->boolean(80), // 80% chance of being active
        ];
    }
}
