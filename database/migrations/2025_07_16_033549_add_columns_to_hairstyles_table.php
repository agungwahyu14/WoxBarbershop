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
            $table->string('name')->after('id');
            $table->text('description')->nullable()->after('name');
            $table->string('bentuk_kepala')->after('description');
            $table->string('tipe_rambut')->after('bentuk_kepala');
            $table->string('image')->nullable()->after('tipe_rambut');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hairstyles', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'bentuk_kepala', 'tipe_rambut', 'image']);
        });
    }
};
