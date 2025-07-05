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
        Schema::create('rides', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('station_location');
            $table->string('destination');
            $table->date('date');
            $table->time('time');
            $table->integer('available_seats');
            $table->boolean('is_exclusive'); // true = exclusive, false = shared
            $table->boolean('is_two_way')->default(false); // true = two-way/return ride
            // Return trip fields (nullable, only used if is_two_way is true)
            $table->string('return_station_location')->nullable();
            $table->string('return_destination')->nullable();
            $table->date('return_date')->nullable();
            $table->time('return_time')->nullable();
            $table->integer('return_available_seats')->nullable();
            $table->boolean('return_is_exclusive')->nullable();
            $table->decimal('go_to_price_per_person', 8, 2)->nullable();
            $table->decimal('return_price_per_person', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rides');
    }
}; 