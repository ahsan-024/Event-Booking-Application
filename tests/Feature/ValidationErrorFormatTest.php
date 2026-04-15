<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ValidationErrorFormatTest extends TestCase
{
    use RefreshDatabase;

    public function test_validation_error_returns_structured_errors(): void
    {
        $user = User::factory()->create();

        // Missing event_id and seats_booked
        $response = $this->actingAs($user)->postJson('/bookings', []);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => [
                         'event_id',
                         'seats_booked',
                     ],
                 ]);
    }

    public function test_event_validation_error_format(): void
    {
        $user = User::factory()->create();

        // Missing all required fields
        $response = $this->actingAs($user)->postJson('/events', []);

        $response->assertStatus(422)
                 ->assertJsonStructure([
                     'errors' => [
                         'title',
                         'location',
                         'event_datetime',
                         'total_seats',
                     ],
                 ]);
    }
}
