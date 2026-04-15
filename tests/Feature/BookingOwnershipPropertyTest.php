<?php

// Feature: event-booking-app, Property 11: Users see only their own bookings

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\User;
use Eris\Generator\ChooseGenerator;
use Eris\TestTrait;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Validates: Requirements 8.3, 10.2
 */
class BookingOwnershipPropertyTest extends TestCase
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

    public function test_users_see_only_their_own_bookings()
    {
        $this->minimumEvaluationRatio(1.0)
            ->forAll(
                new ChooseGenerator(2, 5)
            )
            ->then(function (int $userCount) {
                // Create N users, each with some bookings
                $users = User::factory()->count($userCount)->create();

                foreach ($users as $user) {
                    $bookingCount = rand(1, 3);
                    Booking::factory()->count($bookingCount)->create(['user_id' => $user->id]);
                }

                // For each user, GET /bookings and assert all returned bookings belong to them
                foreach ($users as $user) {
                    $response = $this->actingAs($user)->getJson('/bookings');

                    $response->assertStatus(200);

                    $data = $response->json('data');

                    $this->assertNotNull($data, 'Response should have a data key');

                    foreach ($data as $booking) {
                        $this->assertEquals(
                            $user->id,
                            $booking['user_id'],
                            "Booking user_id ({$booking['user_id']}) should equal authenticated user id ({$user->id})"
                        );
                    }
                }
            });
    }
}
