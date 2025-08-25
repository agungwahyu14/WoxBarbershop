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
        Schema::table('loyalties', function (Blueprint $table) {
            $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            $table->integer('points')->default(0)->after('user_id');
            $table->integer('total_earned')->default(0)->after('points');
            $table->integer('total_redeemed')->default(0)->after('total_earned');
            $table->date('last_transaction_date')->nullable()->after('total_redeemed');
            $table->enum('tier', ['bronze', 'silver', 'gold', 'platinum'])->default('bronze')->after('last_transaction_date');
            $table->json('tier_benefits')->nullable()->after('tier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loyalties', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'user_id',
                'points',
                'total_earned',
                'total_redeemed',
                'last_transaction_date',
                'tier',
                'tier_benefits',
            ]);
        });
    }
};
