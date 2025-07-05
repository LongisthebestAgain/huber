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
            // Add completion status fields
            $table->enum('go_completion_status', ['pending', 'completed', 'cancelled'])->default('pending')->after('go_to_exclusive_price');
            $table->enum('return_completion_status', ['pending', 'completed', 'cancelled'])->default('pending')->after('go_completion_status');
            $table->timestamp('go_completed_at')->nullable()->after('return_completion_status');
            $table->timestamp('return_completed_at')->nullable()->after('go_completed_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn([
                'go_completion_status',
                'return_completion_status',
                'go_completed_at',
                'return_completed_at'
            ]);
        });
    }
};
