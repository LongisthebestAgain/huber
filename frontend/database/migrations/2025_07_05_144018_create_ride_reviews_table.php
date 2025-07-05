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
        Schema::create('ride_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ride_id')->constrained('rides')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('ride_purchase_id')->constrained('ride_purchases')->onDelete('cascade');
            
            // Overall rating (1-5 stars)
            $table->integer('overall_rating')->comment('1-5 stars');
            
            // Specific ratings
            $table->integer('driver_rating')->comment('1-5 stars - Driver professionalism');
            $table->integer('vehicle_rating')->comment('1-5 stars - Vehicle cleanliness and condition');
            $table->integer('punctuality_rating')->comment('1-5 stars - On-time departure and arrival');
            $table->integer('safety_rating')->comment('1-5 stars - Safe driving');
            $table->integer('comfort_rating')->comment('1-5 stars - Ride comfort');
            
            // Review text
            $table->text('review_text')->nullable();
            
            // Trip type (go/return)
            $table->enum('trip_type', ['go', 'return']);
            
            // Review status
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('approved');
            
            $table->timestamps();
            
            // Ensure one review per booking per trip type
            $table->unique(['ride_purchase_id', 'trip_type'], 'unique_booking_review');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ride_reviews');
    }
};
