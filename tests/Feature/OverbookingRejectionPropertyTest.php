<?php

// Feature: event-booking-app, Property 10: Overbooking is rejected

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 7.1, 7.2, 7.3, 14.3
 */
class OverbookingRejectionPropertyTest extends TestCase
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

    public function test_overbooking_is_rejected()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 10),
                new ChooseGenerator(1, 10)
            )
            ->then(function ($availableSeats, $excess) {
                $seatsToBook = $availableSeats + $excess;

                $user = User::factory()->create();
                $event = Event::factory()->create([
                    'created_by'      => $user->id,
                    'total_seats'     => $availableSeats,
                    'available_seats' => $availableSeats,
                ]);

                $response = $this->actingAs($user)->postJson('/bookings', [
                    'event_id'     => $event->id,
                    'seats_booked' => $seatsToBook,
                ]);

                $response->assertStatus(422);

                $response->assertJsonPath('errors.seats_booked.0', 'Not enough available seats.');

                $event->refresh();
                $this->assertEquals(
                    $availableSeats,
                    $event->available_seats,
                    "available_seats should remain {$availableSeats} after rejected overbooking, got {$event->available_seats}"
                );
            });
    }
}
