<?php

use App\Events\TimeLeft;
use App\Models\Timer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $now = Carbon::now()->addSecond();
    $finishTime = Carbon::parse($now);
    $init = intval($finishTime->diffInSeconds(Carbon::now()));

    $time_left = toHours($init);
    dd($time_left, $init);

    return view('welcome');
    $room = Timer::where('status','ongoing')->first();
    event( new TimeLeft($room) );
});

