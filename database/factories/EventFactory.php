<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    public function definition(): array
    {
        $totalSeats = fake()->numberBetween(10, 200);

        return [
            'created_by'      => User::factory(),
            'title'           => fake()->sentence(),
            'description'     => fake()->optional()->paragraph(),
            'location'        => fake()->city(),
            'event_datetime'  => fake()->dateTimeBetween('+1 day', '+1 year'),
            'total_seats'     => $totalSeats,
            'available_seats' => $totalSeats,
        ];
    }
}
