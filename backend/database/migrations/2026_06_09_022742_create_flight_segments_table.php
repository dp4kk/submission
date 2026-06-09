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
        Schema::create('flight_segments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('from');
            $table->string('to');
            $table->date('departure_date');
            $table->time('departure_time');
            $table->date('arrival_date');
            $table->time('arrival_time');
            $table->string('airline');
            $table->string('operating_airline');
            $table->string('flight_number');
            $table->string('aircraft');
            $table->string('terminal')->nullable();
            $table->string('cabin');
            $table->string('baggage');
            $table->string('carry_on');
            $table->string('ticket_number');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('flight_segments');
    }
};
