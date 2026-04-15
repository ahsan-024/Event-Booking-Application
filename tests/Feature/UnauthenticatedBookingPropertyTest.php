<?php

// Feature: event-booking-app, Property 1: Unauthenticated booking operations are denied

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 1.4, 1.5
 */
class UnauthenticatedBookingPropertyTest extends TestCase
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

    public function test_unauthenticated_booking_create_is_denied()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 10)
            )
            ->then(function ($seatsRequested) {
                $user  = User::factory()->create();
                $event = Event::factory()->create([
                    'created_by'      => $user->id,
                    'total_seats'     => 50,
                    'available_seats' => 50,
                ]);

                $countBefore = Booking::count();

                $response = $this->postJson('/bookings', [
                    'event_id'     => $event->id,
                    'seats_booked' => $seatsRequested,
                ]);

                $response->assertStatus(401);
                $this->assertEquals(
                    $countBefore,
                    Booking::count(),
                    "No booking should be created when request is unauthenticated"
                );
            });
    }

    public function test_unauthenticated_booking_cancel_is_denied()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 100)
            )
            ->then(function ($bookingId) {
                $response = $this->deleteJson("/bookings/{$bookingId}");

                $response->assertStatus(401);
            });
    }
}
