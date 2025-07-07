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
        Schema::table('ride_purchases', function (Blueprint $table) {
            // Drop unnecessary columns
            $table->dropColumn(['price_per_seat']);
            
            // Update payment_method to be an enum with only visa and mastercard
            $table->dropColumn('payment_method');
            $table->enum('payment_method', ['visa', 'mastercard'])->default('visa')->after('payment_status');
            
            // Add card details fields
            $table->string('card_last_four', 4)->nullable()->after('payment_method');
            $table->string('card_type')->nullable()->after('card_last_four');
            
            // Add booking reference
            $table->string('booking_reference', 20)->unique()->after('id');
            
            // Add booking date and time
            $table->date('booking_date')->after('booking_reference');
            $table->time('booking_time')->after('booking_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ride_purchases', function (Blueprint $table) {
            // Restore original structure
            $table->decimal('price_per_seat', 8, 2)->after('number_of_seats');
            $table->dropColumn(['card_last_four', 'card_type', 'booking_reference', 'booking_date', 'booking_time']);
            $table->string('payment_method')->default('visa')->after('payment_status');
        });
    }
};
