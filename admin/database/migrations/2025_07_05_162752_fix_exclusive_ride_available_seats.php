<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Set available_seats to 0 for exclusive rides that have bookings
        DB::statement("
            UPDATE rides 
            SET available_seats = 0 
            WHERE is_exclusive = true 
            AND id IN (
                SELECT DISTINCT ride_id 
                FROM ride_purchases 
                WHERE trip_type = 'go'
            )
        ");

        // Set return_available_seats to 0 for return exclusive rides that have bookings
        DB::statement("
            UPDATE rides 
            SET return_available_seats = 0 
            WHERE return_is_exclusive = true 
            AND id IN (
                SELECT DISTINCT ride_id 
                FROM ride_purchases 
                WHERE trip_type = 'return'
            )
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // This migration cannot be safely reversed as we don't know the original seat counts
    }
};
