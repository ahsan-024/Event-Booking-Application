<?php

// Feature: event-booking-app, Property 12: Booking list response contains required fields

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 8.2
 */
class BookingListFieldsPropertyTest extends TestCase
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

    public function test_booking_list_response_contains_required_fields()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 5)
            )
            ->then(function (int $count) {
                $user = User::factory()->create();

                Booking::factory()->count($count)->create(['user_id' => $user->id]);

                $response = $this->actingAs($user)->getJson('/bookings');

                $response->assertStatus(200);

                $data = $response->json('data');

                $this->assertIsArray($data, 'Response should have a data array');
                $this->assertNotEmpty($data, 'Response data should not be empty');

                foreach ($data as $booking) {
                    $this->assertArrayHasKey('seats_booked', $booking, 'Booking item missing "seats_booked"');
                    $this->assertArrayHasKey('status', $booking, 'Booking item missing "status"');
                    $this->assertArrayHasKey('booking_date', $booking, 'Booking item missing "booking_date"');
                    $this->assertArrayHasKey('event', $booking, 'Booking item missing "event"');

                    $event = $booking['event'];
                    $this->assertIsArray($event, 'Booking "event" should be an object/array');
                    $this->assertArrayHasKey('title', $event, 'Event details missing "title"');
                    $this->assertArrayHasKey('location', $event, 'Event details missing "location"');
                    $this->assertArrayHasKey('event_datetime', $event, 'Event details missing "event_datetime"');
                }
            });
    }
}
