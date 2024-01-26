<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timers', function (Blueprint $table) {
            $table->string('session')->primary()->unique();
            $table->time('start_time')->nullable()->default();
            $table->time('time_left')->nullable()->default();
            $table->time('end_time')->nullable()->default();
            $table->integer('period')->default(60);
            $table->enum('status', [ 'pendding', 'paused' ,'ongoing' ,'ended' ])->default('pendding');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timers');
    }
};
