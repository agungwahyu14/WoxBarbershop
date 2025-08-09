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
            Schema::create('pairwise_comparisons', function (Blueprint $table) {
        $table->id();
        $table->foreignId('criterion_id_1')->constrained('criteria')->onDelete('cascade');
        $table->foreignId('criterion_id_2')->constrained('criteria')->onDelete('cascade');
        $table->float('value'); // nilai skala AHP (1 s/d 9)
        $table->timestamps();
    });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pairwise_comparisons');
    }
};
