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
        Schema::table('bookings', function (Blueprint $table) {
            // Add missing columns first
            if (! Schema::hasColumn('bookings', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
            if (! Schema::hasColumn('bookings', 'name')) {
                $table->string('name')->after('user_id');
            }
            if (! Schema::hasColumn('bookings', 'service_id')) {
                $table->foreignId('service_id')->after('name')->constrained()->onDelete('cascade');
            }
            if (! Schema::hasColumn('bookings', 'hairstyle_id')) {
                $table->foreignId('hairstyle_id')->after('service_id')->nullable()->constrained()->onDelete('set null');
            }
            if (! Schema::hasColumn('bookings', 'date_time')) {
                $table->dateTime('date_time')->after('hairstyle_id');
            }
            if (! Schema::hasColumn('bookings', 'queue_number')) {
                $table->integer('queue_number')->after('date_time')->nullable();
            }
            if (! Schema::hasColumn('bookings', 'description')) {
                $table->text('description')->after('queue_number')->nullable();
            }

            // Check and add missing columns only
            if (! Schema::hasColumn('bookings', 'total_price')) {
                $table->decimal('total_price', 10, 2)->nullable();
            }
            if (! Schema::hasColumn('bookings', 'payment_status')) {
                $table->string('payment_status', 50)->default('unpaid');
            }

            // Only modify status enum if the column exists
            if (Schema::hasColumn('bookings', 'status')) {
                DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
            } else {
                // Add status column if it doesn't exist
                $table->enum('status', ['pending', 'confirmed', 'in_progress', 'completed', 'cancelled'])->default('pending');
            }

            // Add indexes for better performance
            $table->index(['date_time', 'status']);
            $table->index('queue_number');
            $table->index('user_id');
        });
    }

    private function foreignKeyExists($table, $foreignKey)
    {
        $result = DB::select('
            SELECT CONSTRAINT_NAME 
            FROM information_schema.KEY_COLUMN_USAGE 
            WHERE TABLE_NAME = ? AND CONSTRAINT_NAME = ? AND TABLE_SCHEMA = ?
        ', [$table, $foreignKey, config('database.connections.mysql.database')]);

        return ! empty($result);
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
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['service_id']);
            $table->dropForeign(['hairstyle_id']);
            $table->dropColumn([
                'user_id', 'name', 'service_id', 'hairstyle_id',
                'date_time', 'queue_number', 'description', 'status',
                'total_price', 'payment_status',
            ]);
        });
    }
};
