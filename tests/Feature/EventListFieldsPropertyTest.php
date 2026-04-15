<?php

// Feature: event-booking-app, Property 5: Event list response contains required fields

namespace Tests\Feature;

use App\Models\Event;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 3.2
 */
class EventListFieldsPropertyTest extends TestCase
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

    public function test_event_list_response_contains_required_fields()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(1, 10)
            )
            ->then(function ($n) {
                Event::factory()->count($n)->create();

                $response = $this->getJson('/events');

                $response->assertStatus(200);

                $data = $response->json('data');

                $this->assertIsArray($data);
                $this->assertNotEmpty($data);

                foreach ($data as $item) {
                    $this->assertArrayHasKey('title', $item, 'Event item missing "title"');
                    $this->assertArrayHasKey('description', $item, 'Event item missing "description"');
                    $this->assertArrayHasKey('location', $item, 'Event item missing "location"');
                    $this->assertArrayHasKey('event_datetime', $item, 'Event item missing "event_datetime"');
                    $this->assertArrayHasKey('available_seats', $item, 'Event item missing "available_seats"');
                }
            });
    }
}
