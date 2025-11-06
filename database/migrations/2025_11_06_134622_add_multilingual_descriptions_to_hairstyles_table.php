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
        Schema::table('hairstyles', function (Blueprint $table) {
            $table->text('description_in')->nullable()->after('description');
            $table->text('description_en')->nullable()->after('description_in');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hairstyles', function (Blueprint $table) {
            $table->dropColumn(['description_in', 'description_en']);
        });
    }
};
