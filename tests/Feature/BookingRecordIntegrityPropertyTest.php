<?php

// Feature: event-booking-app, Property 8: Booking record integrity

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 6.1, 6.2, 6.3, 6.4, 6.5, 6.6
 */
class BookingRecordIntegrityPropertyTest extends TestCase
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

    public function test_booking_record_has_correct_fields()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 10)
            )
            ->then(function ($seatsBooked) {
                $user = User::factory()->create();
                $event = Event::factory()->create([
                    'created_by'      => $user->id,
                    'total_seats'     => 100,
                    'available_seats' => 100,
                ]);

                $response = $this->actingAs($user)->postJson('/bookings', [
                    'event_id'     => $event->id,
                    'seats_booked' => $seatsBooked,
                ]);

                $response->assertStatus(201);

                $data = $response->json();

                $this->assertEquals(
                    $user->id,
                    $data['user_id'],
                    "user_id should equal the authenticated user's ID"
                );

                $this->assertEquals(
                    $event->id,
                    $data['event_id'],
                    "event_id should equal the requested event's ID"
                );

                $this->assertEquals(
                    $seatsBooked,
                    $data['seats_booked'],
                    "seats_booked should equal the requested seat count"
                );

                $this->assertEquals(
                    'booked',
                    $data['status'],
                    "status should be 'booked'"
                );

                $this->assertNotNull(
                    $data['booking_date'],
                    "booking_date should not be null"
                );
            });
    }
}
