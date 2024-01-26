<?php

namespace App\Http\Controllers\Api_v1;

use App\Http\Controllers\BaseController;
use App\Models\Timer;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TimerController extends BaseController
{

    public function create(Request $request) {
        $request->validate([
            'session' => 'required|unique:timers,session',
            'period' => 'required'
        ]);

        $period = intval($request->get('period'));
        $period = $period < 1 ? 1 : $period;
        $start_time = Carbon::now();
        $end_time = $period <= 1 ? $start_time->copy()->addMinute() : $start_time->copy()->addMinutes($period);
        $time_left = $end_time->diffInSeconds($start_time);


        Timer::create([
            'session' => $request->get('session'),
            'start_time' => $start_time,
            'time_left' => $time_left,
            'end_time' => $end_time,
            'status' => 'ongoing',
            'period' => $period
        ]);

        return $this->success([ 'success' => true, 'message' => 'Challange is started' ]);
    }

    public function status(Request $request, $slug) {
        $request->validate([
            'session' => 'required|exists:timers,session',
        ]);
        $status = $slug == 'pause' ? 'pause': 'play';
        $timer = Timer::where('session', $request->get('session'))->first();


        if($status == 'pause') {
            $timer->update([
                'status' => 'paused'
            ]);
        } else {
            
            $endtime_carbon = Carbon::parse($timer->end_time);
            $init_end = intval($endtime_carbon->diffInSeconds( Carbon::now() ));
            $diff_now_endtime = toHours($init_end);

            $timeleft_carbon = Carbon::parse($timer->time_left);
            $init_timeleft = intval($timeleft_carbon->diffInSeconds( Carbon::parse($diff_now_endtime) ));

            $timer->update([
                'status' => 'ongoing',
                'time_left' => $timeleft_carbon->addSecond()->format('H:i:s'),
                'end_time' => Carbon::parse($timer->end_time)->addSeconds($init_timeleft)->addSecond()->format('H:i:s')
            ]);
        }

        return $this->success([ 'success' => true, 'message' => "Challange on " . $status ]);
    }

    public function timeUpdate(Request $request) {
        $request->validate([
            'session' => 'required|exists:timers,session',
            'time' => 'required'
        ]);

        $timer = Timer::where('session', $request->get('session'))->first();

        $newdate = Carbon::create($timer->end_time);
        $input_time = intval($request->get('time'));
        if($input_time > 0) {
            if ($input_time > 1) $newdate->addMinutes($input_time);
            else $newdate->addMinute();
        } else if( $input_time < 0 ) {
            if ($input_time < -1) $newdate->subMinutes($input_time*-1);
            else $newdate->subMinute();
        }

        $timer->update([
            'end_time' => $newdate->format('H:i:s')
        ]);

        return $this->success([ 'success' => true, 'message' => "Challange updated" ]);
    }

    public function destroy(Request $request) {
        $request->validate([
            'session' => 'required|exists:timers,session',
        ]);
        Timer::where('session', $request->get('session'))->delete();
        return $this->success([ 'success' => true, 'message' => "Challange Deleted" ]);
    }

}
