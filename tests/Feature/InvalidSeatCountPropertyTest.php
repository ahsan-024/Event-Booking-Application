<?php

// Feature: event-booking-app, Property 15: Invalid seat count is rejected

namespace Tests\Feature;

use App\Models\User;
use Eris\Generator\ElementsGenerator;
use Eris\TestTrait;
use function Eris\Generator\elements;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 11.3
 */
class InvalidSeatCountPropertyTest extends TestCase
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

    public function test_invalid_seat_count_is_rejected()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                elements([0, -1, -5, -100, 'abc', 'not-a-number', 0.5, -0.5])
            )
            ->then(function ($invalidSeats) {
                $user  = User::factory()->create();
                $event = \App\Models\Event::factory()->create([
                    'created_by'      => $user->id,
                    'total_seats'     => 50,
                    'available_seats' => 50,
                ]);

                $response = $this->actingAs($user)->postJson('/bookings', [
                    'event_id'     => $event->id,
                    'seats_booked' => $invalidSeats,
                ]);

                $response->assertStatus(422);
            });
    }
}
