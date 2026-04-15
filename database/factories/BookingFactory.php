<?php

namespace Database\Factories;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id'      => User::factory(),
            'event_id'     => Event::factory(),
            'seats_booked' => fake()->numberBetween(1, 5),
            'status'       => 'booked',
            'booking_date' => now(),
        ];
    }
}
