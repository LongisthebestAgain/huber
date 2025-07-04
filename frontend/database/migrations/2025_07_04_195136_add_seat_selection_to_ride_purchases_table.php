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
            // Add seat selection fields
            $table->json('selected_seats')->nullable()->after('passenger_details');
            $table->boolean('seats_confirmed')->default(false)->after('selected_seats');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ride_purchases', function (Blueprint $table) {
            $table->dropColumn(['selected_seats', 'seats_confirmed']);
        });
    }
};
