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
        Schema::create('ride_purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->constrained('rides')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('number_of_seats');
            $table->decimal('price_per_seat', 8, 2);
            $table->decimal('total_price', 8, 2);
            $table->enum('payment_status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('payment_method')->default('visa');
            $table->text('special_requests')->nullable();
            $table->string('trip_type'); // 'go', 'return', or 'both'
            $table->json('passenger_details'); // Store passenger information as JSON
            $table->string('contact_phone');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_purchases');
    }
};
