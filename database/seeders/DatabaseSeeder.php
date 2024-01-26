<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Challenge;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        $user = \App\Models\User::factory()->create([
            'name' => 'Room Master',
            'email' => 'room.master@tgcc.com',
        ]);

        // Create challenges
        Challenge::factory(10)->create();

        // create room
        /*$start_time = Carbon::now()->subMinutes(10);
        $end_time = $start_time->copy()->addHours(1);
        $time_left = $end_time->diffInSeconds($start_time);

        $user->rooms()->create([
            'start_time' => $start_time,
            'time_left' => $time_left,
            'end_time' => $end_time,
            'challenge_id' => Str::uuid(),
            'status' => 'ongoing',
        ]);*/

    }
}
