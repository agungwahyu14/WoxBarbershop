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
            $table->string('order_id')->nullable()->after('payment_status');
            $table->text('snap_token')->nullable()->after('order_id');
            $table->timestamp('payment_expired_at')->nullable()->after('snap_token');
            $table->json('midtrans_response')->nullable()->after('payment_expired_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['order_id', 'snap_token', 'payment_expired_at', 'midtrans_response']);
        });
    }
};
