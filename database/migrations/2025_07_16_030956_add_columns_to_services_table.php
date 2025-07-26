<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('services', function (Blueprint $table) {
            if (!Schema::hasColumn('services', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('services', 'description')) {
                $table->text('description')->nullable()->after('name');
            }
            if (!Schema::hasColumn('services', 'price')) {
                $table->decimal('price', 8, 2)->default(0)->after('description');
            }
            if (!Schema::hasColumn('services', 'duration')) {
                $table->integer('duration')->default(30)->after('price')->comment('Duration in minutes');
            }
            if (!Schema::hasColumn('services', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('duration');
            }
        });
    }

    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['name', 'description', 'price', 'duration', 'is_active']);
        });
    }
};
