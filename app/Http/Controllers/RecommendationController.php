<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Hairstyle;
use App\Models\Criteria;
use App\Models\PairwiseComparison;
use App\Models\HairstyleScore;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    public function index(Request $request)
    {
        // Tangkap input dari user
        $bentukKepala = $request->bentuk_kepala;
        $tipeRambut = $request->tipe_rambut;
        $preferensiGaya = $request->preferensi_gaya;

        // Log input
        Log::info('Input filter rekomendasi:', [
            'bentuk_kepala' => $bentukKepala,
            'tipe_rambut' => $tipeRambut,
            'preferensi_gaya' => $preferensiGaya,
        ]);

        // Step 1: Hitung bobot AHP
        $weights = $this->calculateAHPWeights();

        // Step 2: Ambil semua hairstyle yang sesuai filter
        $query = Hairstyle::with('scores');

        if ($bentukKepala) {
            $query->where('bentuk_kepala', $bentukKepala);
        }

        if ($tipeRambut) {
            $query->where('tipe_rambut', $tipeRambut);
        }

        if ($preferensiGaya) {
            $query->where('style_preference', $preferensiGaya);
        }

        $hairstyles = $query->get();
        $results = [];

        // Step 3: Hitung skor rekomendasi berdasarkan bobot
        foreach ($hairstyles as $hairstyle) {
    $totalScore = 0;
    $logDetail = [];

    foreach ($hairstyle->scores as $score) {
    $criteriaId = $score->criterion_id; // ✅ yang benar
    $weight = $weights[$criteriaId] ?? 0;
    $contribution = $weight * $score->score;

    $totalScore += $contribution;

    $logDetail[] = [
        'criteria_id' => $criteriaId,
        'weight' => $weight,
        'score' => $score->score,
        'contribution' => round($contribution, 4),
    ];
}


    $roundedScore = round($totalScore, 4);

    // Log semua skor
    Log::info("Hairstyle ID {$hairstyle->id} ({$hairstyle->name}) => Total Score: $roundedScore", $logDetail);

    // Log khusus jika total score = 0.8325
    if ($roundedScore == 0.8325) {
        Log::info("Potongan rambut dengan skor 0.8325 ditemukan:", [
            'id' => $hairstyle->id,
            'name' => $hairstyle->name,
        ]);
    }

    $results[] = [
        'hairstyle' => $hairstyle,
        'score' => $roundedScore,
    ];
}


        // Step 4: Urutkan berdasarkan skor tertinggi
        usort($results, fn($a, $b) => $b['score'] <=> $a['score']);

        // Log hasil
        Log::info('Total hasil rekomendasi: ' . count($results));

        return view('rekomendasi', compact('results'));
    }

    private function calculateAHPWeights()
    {
        $criterias = Criteria::all();
        $criteriaIds = $criterias->pluck('id')->toArray();
        $n = count($criterias);

        $matrix = [];
        foreach ($criteriaIds as $i) {
            foreach ($criteriaIds as $j) {
                $comparison = PairwiseComparison::where('criterion_id_1', $i)
                    ->where('criterion_id_2', $j)
                    ->first();
                $matrix[$i][$j] = $comparison ? $comparison->value : 1;
            }
        }

        $columnSums = [];
        foreach ($criteriaIds as $j) {
            $columnSums[$j] = 0;
            foreach ($criteriaIds as $i) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        $normalized = [];
        $weights = [];

        foreach ($criteriaIds as $i) {
            $weights[$i] = 0;
            foreach ($criteriaIds as $j) {
                $normalized[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
                $weights[$i] += $normalized[$i][$j];
            }
            $weights[$i] /= $n;
        }

        return $weights;
    }
}
