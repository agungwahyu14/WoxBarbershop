<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HairstyleScoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * 
     * Struktur: hairstyle_id, criterion_id, sub_criterion_id, score
     * 
     * Criterion IDs:
     * - 8 = Bentuk Kepala (sub_criterion_id dari tabel bentuk_kepala)
     * - 9 = Tipe Rambut (sub_criterion_id dari tabel tipe_rambut)
     * - 10 = Preferensi Gaya (sub_criterion_id dari tabel style_preference)
     * 
     * Sub-criteria IDs:
     * Bentuk Kepala: 1=Oval, 2=Bulat, 3=Persegi Panjang, 4=Hati, 5=Kotak, 6=Segitiga
     * Tipe Rambut: 1=Lurus, 2=Bergelombang, 3=Keriting
     * Style Preference: 1=Klasik, 2=Modern, 3=Kasual
     * 
     * Hairstyle Mapping (ID dari database):
     * 47 = Textured Crop, 46 = Long Fringe, 45 = Side Swept Fringe, 44 = Caesar Cut
     * 43 = Fringe, 42 = Undercut, 41 = Crew Cut, 40 = Tapper Fade
     * 39 = Buzzcut, 38 = Quiff, 37 = Pompadour, 36 = Side Part, 34 = French Crop
     */
    public function run(): void
    {
        $scores = [
            // ==================== TEXTURED CROP (ID: 47) ====================
            // Bentuk Wajah (Criterion ID: 8)
            ['hairstyle_id' => 47, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 8], // Oval
            ['hairstyle_id' => 47, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 7], // Bulat
            ['hairstyle_id' => 47, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 8], // Persegi Panjang
            ['hairstyle_id' => 47, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 7], // Hati
            ['hairstyle_id' => 47, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 6], // Kotak
            ['hairstyle_id' => 47, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7], // Segitiga
            // Tipe Rambut (Criterion ID: 9)
            ['hairstyle_id' => 47, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 7], // Lurus
            ['hairstyle_id' => 47, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8], // Bergelombang
            ['hairstyle_id' => 47, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 9], // Keriting
            // Preferensi Gaya (Criterion ID: 10)
            ['hairstyle_id' => 47, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 4], // Klasik
            ['hairstyle_id' => 47, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 9], // Modern
            ['hairstyle_id' => 47, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 8], // Kasual

            // ==================== LONG FRINGE (ID: 46) ====================
            ['hairstyle_id' => 46, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 7],
            ['hairstyle_id' => 46, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 5],
            ['hairstyle_id' => 46, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 9],
            ['hairstyle_id' => 46, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 9],
            ['hairstyle_id' => 46, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 6],
            ['hairstyle_id' => 46, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 46, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 46, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 7],
            ['hairstyle_id' => 46, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 3],
            ['hairstyle_id' => 46, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 5],
            ['hairstyle_id' => 46, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 46, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 4],

            // ==================== SIDE SWEPT FRINGE (ID: 45) ====================
            ['hairstyle_id' => 45, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 45, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 6],
            ['hairstyle_id' => 45, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 8],
            ['hairstyle_id' => 45, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 9],
            ['hairstyle_id' => 45, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 7],
            ['hairstyle_id' => 45, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 8],
            ['hairstyle_id' => 45, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 45, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 45, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 5],
            ['hairstyle_id' => 45, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 45, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 7],
            ['hairstyle_id' => 45, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 6],

            // ==================== CAESAR CUT (ID: 44) ====================
            ['hairstyle_id' => 44, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 7],
            ['hairstyle_id' => 44, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 3],
            ['hairstyle_id' => 44, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 8],
            ['hairstyle_id' => 44, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 7],
            ['hairstyle_id' => 44, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 5],
            ['hairstyle_id' => 44, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 6],
            ['hairstyle_id' => 44, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 44, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 7],
            ['hairstyle_id' => 44, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 6],
            ['hairstyle_id' => 44, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 44, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 6],
            ['hairstyle_id' => 44, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 8],

            // ==================== FRINGE (ID: 43) ====================
            ['hairstyle_id' => 43, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 43, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 5],
            ['hairstyle_id' => 43, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 9],
            ['hairstyle_id' => 43, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 9],
            ['hairstyle_id' => 43, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 6],
            ['hairstyle_id' => 43, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 43, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 43, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 43, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 5],
            ['hairstyle_id' => 43, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 6],
            ['hairstyle_id' => 43, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 43, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 7],

            // ==================== UNDERCUT (ID: 42) ====================
            ['hairstyle_id' => 42, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 42, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 42, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 6],
            ['hairstyle_id' => 42, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 7],
            ['hairstyle_id' => 42, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 8],
            ['hairstyle_id' => 42, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 42, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 42, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 42, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 8],
            ['hairstyle_id' => 42, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 5],
            ['hairstyle_id' => 42, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 42, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 7],

            // ==================== CREW CUT (ID: 41) ====================
            ['hairstyle_id' => 41, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 41, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 4],
            ['hairstyle_id' => 41, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 7],
            ['hairstyle_id' => 41, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 6],
            ['hairstyle_id' => 41, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 7],
            ['hairstyle_id' => 41, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 41, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 41, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 41, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 7],
            ['hairstyle_id' => 41, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 41, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 6],
            ['hairstyle_id' => 41, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 9],

            // ==================== TAPER FADE (ID: 40) ====================
            ['hairstyle_id' => 40, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 40, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 7],
            ['hairstyle_id' => 40, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 7],
            ['hairstyle_id' => 40, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 8],
            ['hairstyle_id' => 40, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 8],
            ['hairstyle_id' => 40, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 8],
            ['hairstyle_id' => 40, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 40, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 40, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 9],
            ['hairstyle_id' => 40, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 7],
            ['hairstyle_id' => 40, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 40, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 8],

            // ==================== BUZZCUT (ID: 39) ====================
            ['hairstyle_id' => 39, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 7],
            ['hairstyle_id' => 39, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 2],
            ['hairstyle_id' => 39, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 5],
            ['hairstyle_id' => 39, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 4],
            ['hairstyle_id' => 39, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 8],
            ['hairstyle_id' => 39, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 6],
            ['hairstyle_id' => 39, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 39, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 39, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 8],
            ['hairstyle_id' => 39, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 7],
            ['hairstyle_id' => 39, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 5],
            ['hairstyle_id' => 39, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 9],

            // ==================== QUIFF (ID: 38) ====================
            ['hairstyle_id' => 38, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 38, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 38, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 5],
            ['hairstyle_id' => 38, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 7],
            ['hairstyle_id' => 38, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 8],
            ['hairstyle_id' => 38, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 38, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 38, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 38, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 6],
            ['hairstyle_id' => 38, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 7],
            ['hairstyle_id' => 38, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 38, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 4],

            // ==================== POMPADOUR (ID: 37) ====================
            ['hairstyle_id' => 37, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 37, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 37, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 3],
            ['hairstyle_id' => 37, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 6],
            ['hairstyle_id' => 37, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 7],
            ['hairstyle_id' => 37, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 6],
            ['hairstyle_id' => 37, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 37, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 7],
            ['hairstyle_id' => 37, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 4],
            ['hairstyle_id' => 37, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 37, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 37, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 3],

            // ==================== SIDE PART (ID: 36) ====================
            ['hairstyle_id' => 36, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 36, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 6],
            ['hairstyle_id' => 36, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 7],
            ['hairstyle_id' => 36, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 8],
            ['hairstyle_id' => 36, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 8],
            ['hairstyle_id' => 36, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 36, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 36, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 36, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 5],
            ['hairstyle_id' => 36, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 9],
            ['hairstyle_id' => 36, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 6],
            ['hairstyle_id' => 36, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 5],

            // ==================== FRENCH CROP (ID: 34) ====================
            ['hairstyle_id' => 34, 'criterion_id' => 8, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 34, 'criterion_id' => 8, 'sub_criterion_id' => 2, 'score' => 6],
            ['hairstyle_id' => 34, 'criterion_id' => 8, 'sub_criterion_id' => 3, 'score' => 9],
            ['hairstyle_id' => 34, 'criterion_id' => 8, 'sub_criterion_id' => 4, 'score' => 8],
            ['hairstyle_id' => 34, 'criterion_id' => 8, 'sub_criterion_id' => 5, 'score' => 7],
            ['hairstyle_id' => 34, 'criterion_id' => 8, 'sub_criterion_id' => 6, 'score' => 7],
            ['hairstyle_id' => 34, 'criterion_id' => 9, 'sub_criterion_id' => 1, 'score' => 8],
            ['hairstyle_id' => 34, 'criterion_id' => 9, 'sub_criterion_id' => 2, 'score' => 8],
            ['hairstyle_id' => 34, 'criterion_id' => 9, 'sub_criterion_id' => 3, 'score' => 7],
            ['hairstyle_id' => 34, 'criterion_id' => 10, 'sub_criterion_id' => 1, 'score' => 6],
            ['hairstyle_id' => 34, 'criterion_id' => 10, 'sub_criterion_id' => 2, 'score' => 9],
            ['hairstyle_id' => 34, 'criterion_id' => 10, 'sub_criterion_id' => 3, 'score' => 9],
        ];

        DB::table('hairstyle_scores')->insert($scores);
    }
}
