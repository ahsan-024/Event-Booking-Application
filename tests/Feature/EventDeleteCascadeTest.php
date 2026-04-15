<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventDeleteCascadeTest extends TestCase
{
    use RefreshDatabase;

    public function test_deleting_event_removes_associated_bookings(): void
    {
        $user  = User::factory()->create();
        $event = Event::factory()->create(['total_seats' => 50, 'available_seats' => 50]);

        // Create two bookings for this event
        Booking::factory()->count(2)->create([
            'event_id' => $event->id,
            'user_id'  => $user->id,
        ]);

        $this->assertDatabaseCount('bookings', 2);

        // Delete the event (authenticated)
        $this->actingAs($user)->deleteJson("/events/{$event->id}")->assertStatus(204);

        // Event is gone
        $this->assertDatabaseMissing('events', ['id' => $event->id]);

        // Bookings are cascade-deleted
        $this->assertDatabaseCount('bookings', 0);
        $this->assertDatabaseMissing('bookings', ['event_id' => $event->id]);
    }
}
