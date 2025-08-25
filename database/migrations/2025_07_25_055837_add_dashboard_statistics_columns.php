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
        Schema::table('dashboards', function (Blueprint $table) {
            $table->string('metric_name')->after('id');
            $table->string('metric_value')->after('metric_name');
            $table->string('metric_type')->default('count')->after('metric_value'); // count, percentage, currency
            $table->date('date')->after('metric_type');
            $table->string('period')->default('daily')->after('date'); // daily, weekly, monthly, yearly
            $table->json('additional_data')->nullable()->after('period');

            $table->unique(['metric_name', 'date', 'period']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dashboards', function (Blueprint $table) {
            $table->dropUnique(['metric_name', 'date', 'period']);
            $table->dropColumn([
                'metric_name',
                'metric_value',
                'metric_type',
                'date',
                'period',
                'additional_data',
            ]);
        });
    }
};
