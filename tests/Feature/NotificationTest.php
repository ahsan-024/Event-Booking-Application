<?php

namespace Tests\Feature;

use App\Mail\BookingConfirmationMail;
use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_booking_confirmation_mail_is_sent(): void
    {
        Mail::fake();

        $user  = User::factory()->create();
        $event = Event::factory()->create([
            'total_seats'     => 20,
            'available_seats' => 20,
        ]);

        $this->actingAs($user)->postJson('/bookings', [
            'event_id'     => $event->id,
            'seats_booked' => 1,
        ])->assertStatus(201);

        Mail::assertSent(BookingConfirmationMail::class);
    }
}
