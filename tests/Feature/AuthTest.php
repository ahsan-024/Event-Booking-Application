<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_register_success(): void
    {
        $response = $this->postJson('/register', [
            'name'                  => 'Jane Doe',
            'email'                 => 'jane@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
                 ->assertJsonFragment(['email' => 'jane@example.com']);
    }

    public function test_login_success(): void
    {
        $user = User::factory()->create([
            'email'    => 'john@example.com',
            'password' => bcrypt('secret123'),
        ]);

        $response = $this->postJson('/login', [
            'email'    => 'john@example.com',
            'password' => 'secret123',
        ]);

        $response->assertStatus(200)
                 ->assertJsonFragment(['email' => 'john@example.com']);
    }

    public function test_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/logout');

        $response->assertStatus(204);
    }

    public function test_login_with_wrong_password_returns_401(): void
    {
        User::factory()->create([
            'email'    => 'bob@example.com',
            'password' => bcrypt('correctpassword'),
        ]);

        $response = $this->postJson('/login', [
            'email'    => 'bob@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401);
    }
}
