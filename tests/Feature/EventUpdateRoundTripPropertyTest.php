<?php

// Feature: event-booking-app, Property 7: Event update persists new values

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 4.1, 4.2, 4.3, 4.4, 4.5
 */
class EventUpdateRoundTripPropertyTest extends TestCase
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

    public function test_event_update_persists_new_values()
    {
        $titles = [
            'Annual Tech Summit', 'Music Festival', 'Art Exhibition',
            'Science Fair', 'Food & Wine Expo', 'Sports Gala',
            'Film Screening', 'Book Launch', 'Charity Auction', 'Dance Recital',
        ];

        $locations = [
            'Grand Hall', 'City Arena', 'Convention Center',
            'Riverside Park', 'Downtown Theater', 'University Campus',
            'Sports Complex', 'Community Center', 'Rooftop Venue', 'Beach Pavilion',
        ];

        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                \Eris\Generator\elements($titles),
                \Eris\Generator\elements($locations),
                new ChooseGenerator(1, 500)
            )
            ->then(function ($newTitle, $newLocation, $newTotalSeats) {
                // Clean up between iterations
                Event::query()->delete();
                User::query()->delete();

                $user = User::factory()->create();

                // Create an initial event
                $event = Event::factory()->create([
                    'created_by' => $user->id,
                ]);

                $newDatetime = now()->addDays(30)->format('Y-m-d H:i:s');

                // Send PUT request with updated values
                $response = $this->actingAs($user)->putJson("/events/{$event->id}", [
                    'title'          => $newTitle,
                    'location'       => $newLocation,
                    'total_seats'    => $newTotalSeats,
                    'event_datetime' => $newDatetime,
                ]);

                $response->assertStatus(200);

                // GET the event and assert updated values are persisted
                $getResponse = $this->actingAs($user)->getJson("/events/{$event->id}");
                $getResponse->assertStatus(200);

                $data = $getResponse->json();

                $this->assertEquals(
                    $newTitle,
                    $data['title'],
                    "title should be updated to '{$newTitle}', got '{$data['title']}'"
                );

                $this->assertEquals(
                    $newLocation,
                    $data['location'],
                    "location should be updated to '{$newLocation}', got '{$data['location']}'"
                );

                $this->assertEquals(
                    $newTotalSeats,
                    $data['total_seats'],
                    "total_seats should be updated to {$newTotalSeats}, got {$data['total_seats']}"
                );
            });
    }
}
