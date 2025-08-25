<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CriteriasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('criteria')->insert([
            ['name' => 'Bentuk Kepala'],
            ['name' => 'Tipe Rambut'],
            ['name' => 'Preferensi Gaya'],
        ]);

    }
}
