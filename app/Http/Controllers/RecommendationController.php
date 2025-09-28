<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Hairstyle;
use App\Models\PairwiseComparison;
use Illuminate\Http\Request;
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
        $query = Hairstyle::with(['scores', 'bentuk_kepala', 'tipe_rambut', 'style_preference']);
        \Log::info('Initial hairstyle query built. $query: '. $query->toSql());

if ($bentukKepala) {
    $query->whereHas('bentuk_kepala', function($q) use ($bentukKepala) {
        $q->where('nama', $bentukKepala);
    });
}

if ($tipeRambut) {
    $query->whereHas('tipe_rambut', function($q) use ($tipeRambut) {
        $q->where('nama', $tipeRambut);
    });
}

if ($preferensiGaya) {
    $query->whereHas('style_preference', function($q) use ($preferensiGaya) {
        $q->where('nama', $preferensiGaya);
    });
}
$hairstyles = $query->get();
\Log::info('Total hairstyles fetched for recommendation: '. $hairstyles->count());
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

          

            $results[] = [
                'hairstyle' => $hairstyle,
                'score' => $roundedScore,
            ];
        }

        // Step 4: Urutkan berdasarkan skor tertinggi
        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        // Log hasil
        Log::info('Total hasil rekomendasi: '.count($results));

        return view('rekomendasi', compact('results'));
    }

    private function calculateAHPWeights()
    {
        $criterias = Criteria::all();
        $criteriaIds = $criterias->pluck('id')->toArray();
        $n = count($criterias);
        \Log::info("Calculating AHP weights for $n criteria.");

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

        // Check consistency
        $this->calculateConsistencyRatio($matrix, $weights, $n);
         
            \Log::info("AHP Weights: ".json_encode($weights));
            \Log::info("AHP Normalized Matrix: ".json_encode($normalized));
            \Log::info("AHP Consistency Ratio: ".json_encode($this->calculateConsistencyRatio($matrix, $weights, $n)));

        return $weights;
    }

    /**
     * Calculate Consistency Ratio untuk memvalidasi AHP
     */
    private function calculateConsistencyRatio($matrix, $weights, $n)
    {
        // Step 1: Calculate weighted sum vector
        $weightedSum = [];
        foreach ($matrix as $i => $row) {
            $weightedSum[$i] = 0;
            foreach ($row as $j => $value) {
                $weightedSum[$i] += $value * $weights[$j];
            }
        }

        // Step 2: Calculate consistency vector
        $consistencyVector = [];
        foreach ($weightedSum as $i => $ws) {
            $consistencyVector[$i] = $ws / $weights[$i];
        }

        // Step 3: Calculate λmax (lambda max)
        $lambdaMax = array_sum($consistencyVector) / $n;

        // Step 4: Calculate CI (Consistency Index)
        $CI = ($lambdaMax - $n) / ($n - 1);

        // Step 5: Random Index values
        $RI = [0, 0, 0.52, 0.89, 1.11, 1.25, 1.35, 1.40, 1.45];

        // Step 6: Calculate CR (Consistency Ratio)
        $CR = $n > 2 ? $CI / $RI[$n - 1] : 0;

        Log::info('AHP Consistency Check:', [
            'lambda_max' => $lambdaMax,
            'CI' => $CI,
            'CR' => $CR,
            'is_consistent' => $CR < 0.1
        ]);

        return $CR;
    }
}
