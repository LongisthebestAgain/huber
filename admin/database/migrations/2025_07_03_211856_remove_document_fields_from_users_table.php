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
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'driver_license_file',
                'vehicle_registration_file', 
                'insurance_certificate_file',
                'terms_accepted'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('driver_license_file')->nullable();
            $table->string('vehicle_registration_file')->nullable();
            $table->string('insurance_certificate_file')->nullable();
            $table->boolean('terms_accepted')->default(false);
        });
    }
};
