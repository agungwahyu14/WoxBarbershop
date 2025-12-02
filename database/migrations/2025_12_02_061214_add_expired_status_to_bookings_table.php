<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Add 'expired' status to the bookings status enum
     */
    public function up(): void
    {
        // Modify the ENUM to include 'expired' status
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled', 'expired') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     * Remove 'expired' status from the bookings status enum
     */
    public function down(): void
    {
        // First update any 'expired' records to 'cancelled' before removing the enum value
        DB::table('bookings')->where('status', 'expired')->update(['status' => 'cancelled']);
        
        // Revert the ENUM to original values
        DB::statement("ALTER TABLE bookings MODIFY COLUMN status ENUM('pending', 'confirmed', 'in_progress', 'completed', 'cancelled') DEFAULT 'pending'");
    }
};
