<?php

namespace Tests\Feature;

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EventCrudTest extends TestCase
{
    use RefreshDatabase;

    // -------------------------------------------------------------------------
    // CREATE
    // -------------------------------------------------------------------------

    public function test_create_event_success(): void
    {
        $user = User::factory()->create();

        $payload = [
            'title'          => 'Laravel Conference',
            'description'    => 'A great PHP event.',
            'location'       => 'Berlin',
            'event_datetime' => now()->addDays(10)->toDateTimeString(),
            'total_seats'    => 100,
        ];

        $response = $this->actingAs($user)->postJson('/events', $payload);

        $response->assertStatus(201)
                 ->assertJsonFragment(['title' => 'Laravel Conference']);

        $this->assertDatabaseHas('events', [
            'title'           => 'Laravel Conference',
            'location'        => 'Berlin',
            'total_seats'     => 100,
            'available_seats' => 100,
            'created_by'      => $user->id,
        ]);
    }

    // -------------------------------------------------------------------------
    // READ – list
    // -------------------------------------------------------------------------

    public function test_read_event_list(): void
    {
        Event::factory()->count(3)->create();

        $response = $this->getJson('/events');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'data' => [
                         '*' => ['id', 'title', 'location', 'event_datetime', 'available_seats'],
                     ],
                     'current_page',
                     'total',
                 ]);
    }

    // -------------------------------------------------------------------------
    // READ – single
    // -------------------------------------------------------------------------

    public function test_read_single_event(): void
    {
        $event = Event::factory()->create(['title' => 'Single Event Test']);

        $response = $this->getJson("/events/{$event->id}");

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'Single Event Test'])
                 ->assertJsonFragment(['id' => $event->id]);
    }

    // -------------------------------------------------------------------------
    // UPDATE
    // -------------------------------------------------------------------------

    public function test_update_event(): void
    {
        $user  = User::factory()->create();
        $event = Event::factory()->create(['title' => 'Old Title', 'location' => 'Old City']);

        $payload = [
            'title'          => 'New Title',
            'location'       => 'New City',
            'event_datetime' => now()->addDays(20)->toDateTimeString(),
            'total_seats'    => 50,
        ];

        $response = $this->actingAs($user)->putJson("/events/{$event->id}", $payload);

        $response->assertStatus(200)
                 ->assertJsonFragment(['title' => 'New Title'])
                 ->assertJsonFragment(['location' => 'New City']);

        $this->assertDatabaseHas('events', [
            'id'       => $event->id,
            'title'    => 'New Title',
            'location' => 'New City',
        ]);
    }

    // -------------------------------------------------------------------------
    // DELETE
    // -------------------------------------------------------------------------

    public function test_delete_event(): void
    {
        $user  = User::factory()->create();
        $event = Event::factory()->create();

        $response = $this->actingAs($user)->deleteJson("/events/{$event->id}");

        $response->assertStatus(204);

        $this->assertDatabaseMissing('events', ['id' => $event->id]);
    }
}
