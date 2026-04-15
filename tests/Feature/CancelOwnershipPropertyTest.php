<?php

// Feature: event-booking-app, Property 14: Users can only cancel their own bookings

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 9.3, 10.3, 10.4
 */
class CancelOwnershipPropertyTest extends TestCase
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

    public function test_users_can_only_cancel_their_own_bookings()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 5)
            )
            ->then(function ($seats) {
                $owner    = User::factory()->create();
                $attacker = User::factory()->create();

                $event = Event::factory()->create([
                    'total_seats'     => 10,
                    'available_seats' => 10,
                ]);

                $booking = Booking::factory()->create([
                    'user_id'      => $owner->id,
                    'event_id'     => $event->id,
                    'seats_booked' => $seats,
                    'status'       => 'booked',
                ]);

                $response = $this->actingAs($attacker)
                    ->deleteJson("/bookings/{$booking->id}");

                $response->assertStatus(403);

                $this->assertDatabaseHas('bookings', [
                    'id'     => $booking->id,
                    'status' => 'booked',
                ]);
            });
    }
}
