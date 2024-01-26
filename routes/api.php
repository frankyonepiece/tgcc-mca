<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ChallengeController;
use App\Http\Controllers\Api\RoomController;
use App\Http\Controllers\Api_v1\TimerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('v1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);

    Route::post('/login', [AuthController::class, 'login']);

    Route::middleware([ 'auth:sanctum' ])->group(function () {

        Route::get('/user', function (Request $request) {
            return $request->user();
        });
        Route::post('/logout', [AuthController::class, 'logout']);

        // tasks
        
    });

    // Version 2
    Route::post('/rooms/create', [ RoomController::class, 'create' ])->name('rooms.store'); // challenge, status: pendding
    Route::post('/rooms/start', [ RoomController::class, 'start' ])->name('rooms.start'); // status: ongoing, start time, end time
    Route::post('/rooms/timer/stop', [ RoomController::class, 'timerStop' ])->name('rooms.timer.stop');
    Route::post('/rooms/timer/play', [ RoomController::class, 'timerPlay' ])->name('rooms.timer.play');

    // Version 1
    Route::post('/timer/create', [ TimerController::class, 'create' ])->name('timer.store');
    Route::post('/timer/status/{slug}', [ TimerController::class, 'status' ])->name('timer.stopstart');
    Route::post('/timer/time', [ TimerController::class, 'timeUpdate' ])->name('timer.time');
    Route::post('/timer/delete', [ TimerController::class, 'destroy' ])->name('timer.destroy');

    // endpoints 
    // ---> Start session <-(session-id) ->(time-left)
    // ---> Update Time <-(Minutes) ->(success)
    // ---> Stop/Start Timer <-(stop/start) ->(success)
    // ---> Delete Timer ->(success)

    

    // Challenge
    Route::get('/challenges', [ ChallengeController::class, 'index' ])->name('challenges.index');
    
});
