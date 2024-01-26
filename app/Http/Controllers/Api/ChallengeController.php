<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\BaseController;
use App\Models\Challenge;
use Illuminate\Http\Request;

class ChallengeController extends BaseController
{
    
    public function index() {
        $challenges = Challenge::with('activeRoom')
            ->get();
        $challenges = $challenges->map(function($challenge) {
            // $room = $challenge->penddingRoom;
            $room = $challenge->activeRoom;

            $res = [
                'id' => $challenge->id,
                'name' => $challenge->name,
            ];
            if (isset($room->id)) {
                $res['room'] = $room->id;
                $res['room_timeleft'] = $room->time_left;
                $res['room_status'] = $room->status;
            }
            return $res;
        });

        return $this->success($challenges);
    }

}
