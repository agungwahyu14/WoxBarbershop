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
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'booking_id')) {
                $table->string('booking_id')->after('id');
            }
            if (!Schema::hasColumn('transactions', 'price')) {
                $table->integer('price')->after('booking_id');
            }
            if (!Schema::hasColumn('transactions', 'total_amount')) {
                $table->integer('total_amount')->after('price');
            }
            if (!Schema::hasColumn('transactions', 'payment_method')) {
                $table->string('payment_method')->nullable()->after('total_amount');
            }
            if (!Schema::hasColumn('transactions', 'payment_status')) {
                $table->string('payment_status')->nullable()->after('payment_method');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn([
                'booking_id',
                'price',
                'total_amount',
                'payment_method',
                'payment_status',
            ]);
        });
    }
};
