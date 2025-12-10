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
        Schema::table('hairstyle_scores', function (Blueprint $table) {
            $table->unsignedBigInteger('sub_criterion_id')->after('criterion_id');
            // sub_criterion_id adalah ID yang merujuk ke:
            // - bentuk_kepala.id (jika criterion_id = 8)
            // - tipe_rambut.id (jika criterion_id = 9)
            // - style_preference.id (jika criterion_id = 10)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hairstyle_scores', function (Blueprint $table) {
            $table->dropColumn('sub_criterion_id');
        });
    }
};
