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
        Schema::table('services', function (Blueprint $table) {
            if (! Schema::hasColumn('services', 'duration')) {
                $table->integer('duration')->default(30)->after('price')->comment('Duration in minutes');
            }
            if (! Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('duration');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['duration', 'is_active']);
        });
    }
};
