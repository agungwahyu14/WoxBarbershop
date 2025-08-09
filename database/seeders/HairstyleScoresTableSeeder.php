<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Hairstyle;
use App\Models\Criteria;
use App\Models\HairstyleScore;

class HairstyleScoresTableSeeder extends Seeder
{
    public function run(): void
    {
        $hairstyles = Hairstyle::all();

        foreach ($hairstyles as $hairstyle) {
            // Skor berdasarkan bentuk kepala
            HairstyleScore::create([
                'hairstyle_id' => $hairstyle->id,
                'criterion_id' => 8, // Bentuk Kepala
                'score' => match (strtolower($hairstyle->bentuk_kepala)) {
                    'oval' => 0.9,
                    'round' => 0.8,
                    'square' => 0.85,
                    'heart' => 0.75,
                    'diamond' => 0.7,
                    default => 0.6,
                }
            ]);

            // Skor berdasarkan tipe rambut
            HairstyleScore::create([
                'hairstyle_id' => $hairstyle->id,
                'criterion_id' => 9, // Tipe Rambut
                'score' => match (strtolower($hairstyle->tipe_rambut)) {
                    'lurus' => 0.9,
                    'wavy' => 0.8,
                    'keriting' => 0.85,
                    'ikal' => 0.75,
                    'coily' => 0.7,
                    default => 0.6,
                }
            ]);

            // Skor preferensi gaya (asumsi preferensi ditentukan berdasarkan nama gaya)
            $style = strtolower($hairstyle->name);
            $score = 0.6; // default

            if (str_contains($style, 'classic') || str_contains($style, 'side part') || str_contains($style, 'pompadour')) {
                $score = 0.9;
            } elseif (str_contains($style, 'fade') || str_contains($style, 'undercut')) {
                $score = 0.85;
            } elseif (str_contains($style, 'buzz') || str_contains($style, 'bowl')) {
                $score = 0.75;
            } elseif (str_contains($style, 'modern') || str_contains($style, 'textured') || str_contains($style, 'crop')) {
                $score = 0.8;
            }

            HairstyleScore::create([
                'hairstyle_id' => $hairstyle->id,
                'criterion_id' => 10, // Preferensi Gaya
                'score' => $score
            ]);
        }
    }
}
