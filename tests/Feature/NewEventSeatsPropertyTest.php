<?php

// Feature: event-booking-app, Property 2: New event available seats equals total seats

namespace Tests\Feature;

use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 2.7
 */
class NewEventSeatsPropertyTest extends TestCase
{
    use RefreshDatabase;
    use TestTrait;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        // Load Eris generator functions (replaces @beforeClass annotation for PHPUnit 10)
        self::erisSetupBeforeClass();
    }

    protected function setUp(): void
    {
        parent::setUp();
        // Initialize Eris random/iteration state (replaces @before annotation for PHPUnit 10)
        $this->erisSetup();
    }

    protected function tearDown(): void
    {
        // Run Eris teardown (replaces @after annotation for PHPUnit 10)
        $this->erisTeardown();
        parent::tearDown();
    }

    public function test_new_event_available_seats_equals_total_seats()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 1000)
            )
            ->then(function ($totalSeats) {
                $user = User::factory()->create();

                $response = $this->actingAs($user)->postJson('/events', [
                    'title'          => 'Test Event',
                    'location'       => 'Test Location',
                    'event_datetime' => now()->addDays(7)->toDateTimeString(),
                    'total_seats'    => $totalSeats,
                ]);

                $response->assertStatus(201);

                $data = $response->json();
                $this->assertEquals(
                    $totalSeats,
                    $data['available_seats'],
                    "available_seats ({$data['available_seats']}) should equal total_seats ({$totalSeats})"
                );
            });
    }
}
