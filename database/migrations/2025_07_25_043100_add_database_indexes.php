<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Add foreign key constraints and indexes using correct column names
            if (Schema::hasColumn('transactions', 'booking_id')) {
                $table->index('booking_id');
            }
            if (Schema::hasColumn('transactions', 'created_at')) {
                $table->index('created_at');
            }
            if (Schema::hasColumn('transactions', 'payment_method')) {
                $table->index('payment_method');
            }
            if (Schema::hasColumn('transactions', 'payment_status')) {
                $table->index('payment_status');
            }
        });

        Schema::table('services', function (Blueprint $table) {
            // Add indexes for better performance if columns exist
            if (Schema::hasColumn('services', 'name')) {
                $table->index('name');
            }
            if (Schema::hasColumn('services', 'price')) {
                $table->index('price');
            }
        });

        Schema::table('hairstyles', function (Blueprint $table) {
            // Add indexes if columns exist
            if (Schema::hasColumn('hairstyles', 'name')) {
                $table->index('name');
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Add indexes - these should exist
            if (Schema::hasColumn('users', 'email') && ! $this->indexExists('users', 'users_email_index')) {
                $table->index('email');
            }
            if (Schema::hasColumn('users', 'no_telepon') && ! $this->indexExists('users', 'users_no_telepon_index')) {
                $table->index('no_telepon');
            }
        });
    }

    private function indexExists($table, $index)
    {
        $result = DB::select('
            SELECT INDEX_NAME 
            FROM information_schema.STATISTICS 
            WHERE TABLE_NAME = ? AND INDEX_NAME = ? AND TABLE_SCHEMA = ?
        ', [$table, $index, config('database.connections.mysql.database')]);

        return ! empty($result);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex(['user_id', 'status']);
            $table->dropIndex(['booking_id']);
            $table->dropIndex(['transaction_date']);
            $table->dropIndex(['payment_method']);
        });

        Schema::table('services', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['price', 'duration']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('hairstyles', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropIndex(['is_active']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['no_telepon']);
        });
    }
};
