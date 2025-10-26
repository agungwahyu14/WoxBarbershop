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
        Schema::table('bookings', function (Blueprint $table) {
            // Add payment_status column if it doesn't exist
            if (!Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status', 50)->default('pending')->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            if (Schema::hasColumn('bookings', 'payment_status')) {
                $table->dropColumn('payment_status');
            }
        });
    }
};
