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
            // Update payment_method to include QR payment
            $table->dropColumn('payment_method');
            $table->enum('payment_method', ['visa', 'mastercard', 'qr'])->default('visa')->after('payment_status');
            
            // Add credit card fields (hashed)
            $table->string('card_number_hash')->nullable()->after('payment_method');
            $table->string('card_expiry_hash')->nullable()->after('card_number_hash');
            $table->string('card_cvv_hash')->nullable()->after('card_expiry_hash');
            $table->string('card_holder_name')->nullable()->after('card_cvv_hash');
            
            // Add payment completion timestamp
            $table->timestamp('payment_completed_at')->nullable()->after('card_holder_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ride_purchases', function (Blueprint $table) {
            $table->dropColumn([
                'card_number_hash',
                'card_expiry_hash',
                'card_cvv_hash',
                'card_holder_name',
                'payment_completed_at'
            ]);
            
            $table->dropColumn('payment_method');
            $table->string('payment_method')->default('visa')->after('payment_status');
        });
    }
};
