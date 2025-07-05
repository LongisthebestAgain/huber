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
            // Remove unnecessary payment fields
            $table->dropColumn([
                'card_last_four',
                'card_type',
                'payment_completed_at'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ride_purchases', function (Blueprint $table) {
            // Restore the removed columns
            $table->string('card_last_four', 4)->nullable()->after('card_holder_name');
            $table->string('card_type')->nullable()->after('card_last_four');
            $table->timestamp('payment_completed_at')->nullable()->after('card_type');
        });
    }
};
