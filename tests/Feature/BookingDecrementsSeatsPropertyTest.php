<?php

// Feature: event-booking-app, Property 9: Booking decrements available seats

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\Generator\BindGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 6.7
 */
class BookingDecrementsSeatsPropertyTest extends TestCase
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

    public function test_booking_decrements_available_seats()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(10, 100),
                new ChooseGenerator(1, 5)
            )
            ->then(function ($totalSeats, $seatsToBook) {
                // Ensure seats_to_book fits within available seats (cap at half of total)
                $seatsToBook = max(1, min($seatsToBook, (int) ($totalSeats / 2)));

                $user = User::factory()->create();
                $event = Event::factory()->create([
                    'created_by'      => $user->id,
                    'total_seats'     => $totalSeats,
                    'available_seats' => $totalSeats,
                ]);

                $response = $this->actingAs($user)->postJson('/bookings', [
                    'event_id'     => $event->id,
                    'seats_booked' => $seatsToBook,
                ]);

                $response->assertStatus(201);

                $eventResponse = $this->actingAs($user)->getJson("/events/{$event->id}");
                $eventResponse->assertStatus(200);

                $eventData = $eventResponse->json();
                $expectedAvailable = $totalSeats - $seatsToBook;

                $this->assertEquals(
                    $expectedAvailable,
                    $eventData['available_seats'],
                    "available_seats ({$eventData['available_seats']}) should equal total_seats ({$totalSeats}) - seats_booked ({$seatsToBook}) = {$expectedAvailable}"
                );
            });
    }
}
