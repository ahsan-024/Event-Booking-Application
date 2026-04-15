<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_full_booking_flow(): void
    {
        $user  = User::factory()->create();
        $event = Event::factory()->create([
            'total_seats'     => 50,
            'available_seats' => 50,
        ]);

        $response = $this->actingAs($user)->postJson('/bookings', [
            'event_id'     => $event->id,
            'seats_booked' => 3,
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('bookings', [
            'user_id'      => $user->id,
            'event_id'     => $event->id,
            'seats_booked' => 3,
            'status'       => 'booked',
        ]);

        $this->assertDatabaseHas('events', [
            'id'              => $event->id,
            'available_seats' => 47,
        ]);
    }

    public function test_cancellation_flow(): void
    {
        $user  = User::factory()->create();
        $event = Event::factory()->create([
            'total_seats'     => 50,
            'available_seats' => 47,
        ]);

        $booking = Booking::factory()->create([
            'user_id'      => $user->id,
            'event_id'     => $event->id,
            'seats_booked' => 3,
            'status'       => 'booked',
        ]);

        $response = $this->actingAs($user)->deleteJson("/bookings/{$booking->id}");

        $response->assertStatus(204);

        $this->assertDatabaseHas('bookings', [
            'id'     => $booking->id,
            'status' => 'cancelled',
        ]);

        $this->assertDatabaseHas('events', [
            'id'              => $event->id,
            'available_seats' => 50,
        ]);
    }
}
