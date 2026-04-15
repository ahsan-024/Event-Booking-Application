<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create();

        Event::factory()->count(10)->create([
            'created_by' => $user->id,
        ]);
    }
}
