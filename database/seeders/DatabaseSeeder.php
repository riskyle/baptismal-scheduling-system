<?php

namespace Database\Seeders;

use App\Models\Schedule;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        Schedule::factory()->create([
            "sched_number" => 2,
            "sched_time" => "8:00",
            "sched_slot" => 5,
        ]);
        Schedule::factory()->create([
            "sched_number" => 2,
            "sched_time" => "13:00",
            "sched_slot" => 5,
        ]);
        Schedule::factory()->create([
            "sched_number" => 4,
            "sched_time" => "8:00",
            "sched_slot" => 5,
        ]);
        Schedule::factory()->create([
            "sched_number" => 4,
            "sched_time" => "13:00",
            "sched_slot" => 5,
        ]);
    }
}
