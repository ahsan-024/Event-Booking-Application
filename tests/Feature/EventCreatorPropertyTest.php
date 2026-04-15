<?php

// Feature: event-booking-app, Property 3: Event creation records the creator

namespace Tests\Feature;

use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 2.8
 */
class EventCreatorPropertyTest extends TestCase
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

    public function test_event_creation_records_the_creator()
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
                    $user->id,
                    $data['created_by'],
                    "created_by ({$data['created_by']}) should equal the authenticated user's id ({$user->id})"
                );
            });
    }
}
