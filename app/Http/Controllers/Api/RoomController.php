<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Challenge;
use App\Models\Room;
use App\Models\Task;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends BaseController
{
    public $user;
    
    public function __construct() {
        $this->user = User::first();
    }
    
    public function create(Request $request) {
        $challenge_id = $request->has('challenge_id') ? $request->get('challenge_id') : Str::uuid();

        $room = $this->user->rooms()->create([
            'challenge_id' => $challenge_id,
        ]);

        return $this->success([ 
            'success' => 'The Room is waiting for players',
            'challege' => $challenge_id,
            'room_id' => $room->id,
            'room_status' => 'pendding',
        ]);
    }

    public function start(Request $request) {
        $request->validate([
            'challenge_id' => 'required|exists:challenges,id',
            'room_id' => 'required|exists:rooms,id',
        ]);

        $start_time = Carbon::now();
        $end_time = $start_time->copy()->addHours(1);
        $time_left = $end_time->diffInSeconds($start_time);
        
        $this->user->rooms()
            ->where('challenge_id', $request->get('challenge_id'))
            ->where('id', $request->get('room_id'))
            ->update([
                'start_time' => $start_time,
                'time_left' => $time_left,
                'end_time' => $end_time,
                'status' => 'ongoing',
            ]);

        $init = intval($time_left);
        $day = floor($init / 86400);
        $hours = floor(($init -($day*86400)) / 3600);
        $minutes = floor(($init / 60) % 60);
        $seconds = $init % 60;
        $time_left = "$hours:$minutes:$seconds";

        return $this->success([ 
            'success' => 'The Room is started',
            'start_time' => $start_time,
            'time_left' => $time_left,
            'end_time' => $end_time,
            'status' => 'ongoing',
        ]);
    }

    public function timerStop(Request $request) {

    }

    public function timerPlay(Request $request) {

    }


}
