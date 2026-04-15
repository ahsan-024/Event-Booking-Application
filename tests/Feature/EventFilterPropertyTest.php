<?php

// Feature: event-booking-app, Property 6: Event filters return only matching events

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 3.3, 3.4
 */
class EventFilterPropertyTest extends TestCase
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

    public function test_date_filter_returns_only_matching_events()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 30)
            )
            ->then(function ($daysOffset) {
                // Clean up between iterations
                Event::query()->delete();
                User::query()->delete();

                $user = User::factory()->create();
                $targetDate = now()->addDays($daysOffset)->format('Y-m-d');

                // Create events on the target date
                Event::factory()->count(3)->create([
                    'created_by'     => $user->id,
                    'event_datetime' => $targetDate . ' 10:00:00',
                ]);

                // Create events on other dates (different offset to avoid collision)
                $otherOffset = ($daysOffset % 30) + 31; // always different from daysOffset
                Event::factory()->count(2)->create([
                    'created_by'     => $user->id,
                    'event_datetime' => now()->addDays($otherOffset)->format('Y-m-d') . ' 10:00:00',
                ]);

                $response = $this->getJson('/events?date=' . $targetDate);
                $response->assertStatus(200);

                $events = $response->json('data');

                $this->assertNotEmpty($events, "Expected at least one event for date {$targetDate}");

                foreach ($events as $event) {
                    $eventDate = date('Y-m-d', strtotime($event['event_datetime']));
                    $this->assertEquals(
                        $targetDate,
                        $eventDate,
                        "Event date {$eventDate} should match filter date {$targetDate}"
                    );
                }
            });
    }

    public function test_location_filter_returns_only_matching_events()
    {
        $locations = [
            'Springfield', 'Shelbyville', 'Capital City',
            'Ogdenville', 'North Haverbrook', 'Brockway',
            'Cypress Creek', 'New New York', 'Arlen', 'Quahog',
        ];

        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                \Eris\Generator\elements($locations)
            )
            ->then(function ($targetLocation) {
                // Clean up between iterations
                Event::query()->delete();
                User::query()->delete();

                $user = User::factory()->create();

                // Create events with the target location
                Event::factory()->count(3)->create([
                    'created_by' => $user->id,
                    'location'   => $targetLocation . ' Arena',
                ]);

                // Create events with a clearly different location
                Event::factory()->count(2)->create([
                    'created_by' => $user->id,
                    'location'   => 'Completely Different Venue XYZ',
                ]);

                $response = $this->getJson('/events?location=' . urlencode($targetLocation));
                $response->assertStatus(200);

                $events = $response->json('data');

                $this->assertNotEmpty($events, "Expected at least one event for location containing '{$targetLocation}'");

                foreach ($events as $event) {
                    $this->assertStringContainsStringIgnoringCase(
                        $targetLocation,
                        $event['location'],
                        "Event location '{$event['location']}' should contain filter string '{$targetLocation}'"
                    );
                }
            });
    }
}
