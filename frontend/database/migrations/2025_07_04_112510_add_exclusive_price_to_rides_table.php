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
            // Add exclusive price fields
            $table->decimal('go_to_exclusive_price', 10, 2)->nullable()->after('go_to_price_per_person');
            $table->decimal('return_exclusive_price', 10, 2)->nullable()->after('return_price_per_person');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rides', function (Blueprint $table) {
            $table->dropColumn(['go_to_exclusive_price', 'return_exclusive_price']);
        });
    }
};
