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
        Schema::table('driver_documents', function (Blueprint $table) {
            $table->string('vehicle_photo_1')->nullable();
            $table->string('vehicle_photo_2')->nullable();
            $table->string('vehicle_photo_3')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('driver_documents', function (Blueprint $table) {
            $table->dropColumn(['vehicle_photo_1', 'vehicle_photo_2', 'vehicle_photo_3']);
        });
    }
};
