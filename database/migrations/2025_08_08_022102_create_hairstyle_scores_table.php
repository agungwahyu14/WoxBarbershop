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
        Schema::create('hairstyle_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hairstyle_id')->constrained()->onDelete('cascade');
            $table->foreignId('criterion_id')->constrained()->onDelete('cascade');
            $table->float('score'); // skor alternatif pada kriteria
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hairstyle_scores');
    }
};
