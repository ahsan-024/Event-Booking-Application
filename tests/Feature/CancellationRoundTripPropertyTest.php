<?php

// Feature: event-booking-app, Property 13: Cancellation updates status and restores seats (round-trip)

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 9.1, 9.2
 */
class CancellationRoundTripPropertyTest extends TestCase
{
    use RefreshDatabase;
    use TestTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::erisSetupBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->erisSetup();
    }

    protected function tearDown(): void
    {
        $this->erisTeardown();
        parent::tearDown();
    }

    public function test_cancellation_updates_status_and_restores_seats()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(10, 50),
                new ChooseGenerator(1, 5)
            )
            ->then(function ($totalSeats, $seatsToBook) {
                // Ensure seats_to_book fits within available seats
                $seatsToBook = max(1, min($seatsToBook, $totalSeats));

                $user = User::factory()->create();
                $event = Event::factory()->create([
                    'created_by'      => $user->id,
                    'total_seats'     => $totalSeats,
                    'available_seats' => $totalSeats,
                ]);

                // Step 1: Book seats
                $bookResponse = $this->actingAs($user)->postJson('/bookings', [
                    'event_id'     => $event->id,
                    'seats_booked' => $seatsToBook,
                ]);
                $bookResponse->assertStatus(201);
                $bookingId = $bookResponse->json('id');

                // Step 2: Cancel the booking
                $cancelResponse = $this->actingAs($user)->deleteJson("/bookings/{$bookingId}");
                $cancelResponse->assertStatus(204);

                // Step 3: Assert booking status is 'cancelled'
                $booking = Booking::find($bookingId);
                $this->assertEquals(
                    'cancelled',
                    $booking->status,
                    "Booking status should be 'cancelled' after cancellation"
                );

                // Step 4: Assert available_seats restored to total_seats
                $event->refresh();
                $this->assertEquals(
                    $totalSeats,
                    $event->available_seats,
                    "available_seats ({$event->available_seats}) should be restored to total_seats ({$totalSeats}) after cancellation"
                );
            });
    }
}
