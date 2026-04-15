<?php

// Feature: event-booking-app, Property 4: Required event fields are enforced

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 2.2, 2.3, 2.4, 2.5, 2.6, 4.6, 11.2
 */
class EventValidationPropertyTest extends TestCase
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

    public function test_missing_required_field_returns_422_and_no_event_persisted()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                \Eris\Generator\elements(['title', 'location', 'event_datetime', 'total_seats'])
            )
            ->then(function (string $fieldToOmit) {
                $user = User::factory()->create();

                $payload = [
                    'title'          => 'Test Event',
                    'location'       => 'Test Location',
                    'event_datetime' => now()->addDays(7)->toDateTimeString(),
                    'total_seats'    => 50,
                ];

                unset($payload[$fieldToOmit]);

                $countBefore = Event::count();

                $response = $this->actingAs($user)->postJson('/events', $payload);

                $response->assertStatus(422);

                $this->assertEquals(
                    $countBefore,
                    Event::count(),
                    "No event should be persisted when required field '{$fieldToOmit}' is missing"
                );
            });
    }

    public function test_invalid_event_datetime_returns_422_and_no_event_persisted()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                \Eris\Generator\elements(['not-a-date', 'yesterday', '2000-01-01 00:00:00', 'invalid'])
            )
            ->then(function (string $invalidDatetime) {
                $user = User::factory()->create();

                $datetime = $invalidDatetime === 'yesterday'
                    ? now()->subDay()->toDateTimeString()
                    : $invalidDatetime;

                $countBefore = Event::count();

                $response = $this->actingAs($user)->postJson('/events', [
                    'title'          => 'Test Event',
                    'location'       => 'Test Location',
                    'event_datetime' => $datetime,
                    'total_seats'    => 50,
                ]);

                $response->assertStatus(422);

                $this->assertEquals(
                    $countBefore,
                    Event::count(),
                    "No event should be persisted when event_datetime is invalid or in the past: '{$invalidDatetime}'"
                );
            });
    }

    public function test_invalid_total_seats_returns_422_and_no_event_persisted()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                \Eris\Generator\elements([0, -1, -100, 'not-a-number'])
            )
            ->then(function ($invalidSeats) {
                $user = User::factory()->create();

                $countBefore = Event::count();

                $response = $this->actingAs($user)->postJson('/events', [
                    'title'          => 'Test Event',
                    'location'       => 'Test Location',
                    'event_datetime' => now()->addDays(7)->toDateTimeString(),
                    'total_seats'    => $invalidSeats,
                ]);

                $response->assertStatus(422);

                $this->assertEquals(
                    $countBefore,
                    Event::count(),
                    "No event should be persisted when total_seats is invalid: '{$invalidSeats}'"
                );
            });
    }
}
