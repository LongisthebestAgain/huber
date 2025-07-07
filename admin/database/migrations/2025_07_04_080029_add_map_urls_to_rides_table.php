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
        Schema::table('rides', function (Blueprint $table) {
            $table->string('station_location_map_url')->nullable();
            $table->string('destination_map_url')->nullable();
            $table->string('return_station_location_map_url')->nullable();
            $table->string('return_destination_map_url')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn('station_location_map_url');
            $table->dropColumn('destination_map_url');
            $table->dropColumn('return_station_location_map_url');
            $table->dropColumn('return_destination_map_url');
        });
    }
};
