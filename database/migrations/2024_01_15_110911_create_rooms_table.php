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
        Schema::create('rooms', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->time('start_time')->nullable()->default();
            $table->time('time_left')->nullable()->default();
            $table->time('end_time')->nullable()->default();
            $table->enum('status', [ 'pendding', 'ongoing' ,'ended' ])->default('pendding');
            $table->foreignUuid('challenge_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
