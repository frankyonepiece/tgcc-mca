<?php

namespace App\Console;

use App\Events\TimeLeft;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();

        $schedule->call(function () {
            // $table_name = 'rooms'; $primary_key = 'id';
            $table_name = 'timers'; $primary_key = 'session';

            $rooms = DB::table($table_name)
                ->where('status', 'ongoing')
                ->whereTime('end_time' ,'>', Carbon::now())
                ->get();


            foreach ($rooms as $room) {
                $finishTime = Carbon::parse($room->end_time);
                $init = intval($finishTime->diffInSeconds(Carbon::now()));
                
                $day = floor($init / 86400);
                $hours = floor(($init -($day*86400)) / 3600);
                $minutes = floor(($init / 60) % 60);
                $seconds = $init % 60;
                $time_left = "$hours:$minutes:$seconds";

                if ($init == 0) {
                    DB::table($table_name)
                    ->where($primary_key, $room->{$primary_key})
                    ->update([ 'status' => 'ended', 'time_left' => '00:00' ]);
                    $room->time_left = '00:00:00';
                } else {
                    DB::table($table_name)
                        ->where($primary_key, $room->{$primary_key})
                        ->update([ 'time_left' => $time_left ]);
                }
                event( new TimeLeft($room) );
            }
        })->everySecond();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
