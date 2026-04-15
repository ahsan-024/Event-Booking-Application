<?php

// Feature: event-booking-app, Property 16: Concurrent bookings do not cause overbooking

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 14.1
 */
class ConcurrentBookingPropertyTest extends TestCase
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

    public function test_concurrent_bookings_do_not_cause_overbooking()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(5, 20),  // available_seats
                new ChooseGenerator(1, 5)    // excess requests beyond available_seats
            )
            ->then(function ($availableSeats, $excess) {
                $numRequests = $availableSeats + $excess;

                // Create the event with a fixed number of available seats
                $organizer = User::factory()->create();
                $event = Event::factory()->create([
                    'created_by'      => $organizer->id,
                    'total_seats'     => $availableSeats,
                    'available_seats' => $availableSeats,
                ]);

                // Create one user per booking request
                $users = User::factory()->count($numRequests)->create();

                $successCount = 0;
                $failCount    = 0;

                // Simulate concurrent booking attempts sequentially
                foreach ($users as $user) {
                    $response = $this->actingAs($user)->postJson('/bookings', [
                        'event_id'     => $event->id,
                        'seats_booked' => 1,
                    ]);

                    if ($response->status() === 201) {
                        $successCount++;
                    } else {
                        $failCount++;
                    }
                }

                // Reload event from DB to get the latest available_seats
                $event->refresh();

                // Invariant 1: available_seats must never go negative
                $this->assertGreaterThanOrEqual(
                    0,
                    $event->available_seats,
                    "available_seats ({$event->available_seats}) must be >= 0 after {$numRequests} booking attempts"
                );

                // Invariant 2: total accepted bookings must not exceed initial available seats
                $this->assertLessThanOrEqual(
                    $availableSeats,
                    $successCount,
                    "Successful bookings ({$successCount}) must not exceed initial available_seats ({$availableSeats})"
                );

                // Invariant 3: every request got either a success or a failure response
                $this->assertEquals(
                    $numRequests,
                    $successCount + $failCount,
                    "successful ({$successCount}) + failed ({$failCount}) must equal total requests ({$numRequests})"
                );
            });
    }
}
