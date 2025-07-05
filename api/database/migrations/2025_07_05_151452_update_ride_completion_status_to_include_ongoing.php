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
        // Update the enum values to include 'ongoing'
        DB::statement("ALTER TABLE rides MODIFY COLUMN go_completion_status ENUM('pending', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending'");
        DB::statement("ALTER TABLE rides MODIFY COLUMN return_completion_status ENUM('pending', 'ongoing', 'completed', 'cancelled') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE rides MODIFY COLUMN go_completion_status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
        DB::statement("ALTER TABLE rides MODIFY COLUMN return_completion_status ENUM('pending', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
