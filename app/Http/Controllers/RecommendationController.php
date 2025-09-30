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
        $bentukKepala   = $request->bentuk_kepala;
        $tipeRambut     = $request->tipe_rambut;
        $preferensiGaya = $request->preferensi_gaya;

        Log::info('Input filter rekomendasi:', [
            'bentuk_kepala'   => $bentukKepala,
            'tipe_rambut'     => $tipeRambut,
            'preferensi_gaya' => $preferensiGaya,
        ]);

        // Step 1: Hitung bobot AHP (dan simpan di DB)
        [$weights, $CR] = $this->calculateAHPWeights();

        // Step 2: Ambil semua hairstyle yang sesuai filter
        $query = Hairstyle::with(['scores', 'bentuk_kepala', 'tipe_rambut', 'style_preference']);
        Log::info('Initial hairstyle query built: '.$query->toSql());

        if ($bentukKepala) {
            $query->whereHas('bentuk_kepala', fn ($q) => $q->where('nama', $bentukKepala));
        }
        if ($tipeRambut) {
            $query->whereHas('tipe_rambut', fn ($q) => $q->where('nama', $tipeRambut));
        }
        if ($preferensiGaya) {
            $query->whereHas('style_preference', fn ($q) => $q->where('nama', $preferensiGaya));
        }

        $hairstyles = $query->get();
        Log::info('Total hairstyles fetched for recommendation: '.$hairstyles->count());

        // Step 3: Hitung skor rekomendasi
        $results = [];
        foreach ($hairstyles as $hairstyle) {
            $totalScore = 0;
            $logDetail  = [];

            foreach ($hairstyle->scores as $score) {
                // Pastikan konsisten dengan DB: criterion_id (atau criteria_id kalau di migration)
                $criteriaId   = $score->criterion_id;
                $weight       = $weights[$criteriaId] ?? 0;
                $contribution = $weight * $score->score;

                $totalScore += $contribution;

                $logDetail[] = [
                    'criteria_id'  => $criteriaId,
                    'weight'       => $weight,
                    'score'        => $score->score,
                    'contribution' => round($contribution, 4),
                ];
            }

            $roundedScore = round($totalScore, 4);

            Log::info("Hairstyle ID {$hairstyle->id} ({$hairstyle->name}) => Total Score: $roundedScore", $logDetail);

            $results[] = [
                'hairstyle' => $hairstyle,
                'score'     => $roundedScore,
            ];
        }

        // Step 4: Urutkan berdasarkan skor tertinggi
        usort($results, fn ($a, $b) => $b['score'] <=> $a['score']);

        Log::info('Total hasil rekomendasi: '.count($results));

        return view('rekomendasi', compact('results', 'CR'));
    }

    private function calculateAHPWeights()
    {
        $criterias   = Criteria::all();
        $criteriaIds = $criterias->pluck('id')->toArray();
        $n           = count($criterias);
        Log::info("Calculating AHP weights for $n criteria.");

        // Ambil semua pairwise comparison sekaligus
        $comparisons = PairwiseComparison::all()->keyBy(fn ($c) => $c->criterion_id_1.'-'.$c->criterion_id_2);

        // Bangun matriks
        $matrix = [];
foreach ($criteriaIds as $i) {
    foreach ($criteriaIds as $j) {
        if ($i === $j) {
            $matrix[$i][$j] = 1; // diagonal selalu 1
        } elseif (isset($comparisons[$i.'-'.$j])) {
            $matrix[$i][$j] = $comparisons[$i.'-'.$j]->value;
        } elseif (isset($comparisons[$j.'-'.$i])) {
            // ambil kebalikannya kalau data (j,i) ada
            $matrix[$i][$j] = 1 / $comparisons[$j.'-'.$i]->value;
        } else {
            $matrix[$i][$j] = 1; // default kalau tidak ada data sama sekali
        }
    }
}

        // Hitung jumlah kolom
        $columnSums = [];
        foreach ($criteriaIds as $j) {
            $columnSums[$j] = 0;
            foreach ($criteriaIds as $i) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalisasi & hitung weight
        $weights = [];
        foreach ($criteriaIds as $i) {
            $weights[$i] = 0;
            foreach ($criteriaIds as $j) {
                $weights[$i] += $matrix[$i][$j] / $columnSums[$j];
            }
            $weights[$i] /= $n;

            // Simpan ke tabel criteria
            Criteria::where('id', $i)->update(['weight' => $weights[$i]]);
        }

        // Hitung Consistency Ratio
        $CR = $this->calculateConsistencyRatio($matrix, $weights, $n);

        Log::info("AHP Weights updated in DB: ".json_encode($weights));
        Log::info("AHP Consistency Ratio: ".$CR);

        return [$weights, $CR];
    }

    private function calculateConsistencyRatio($matrix, $weights, $n)
    {
        // Weighted sum vector
        $weightedSum = [];
        foreach ($matrix as $i => $row) {
            $weightedSum[$i] = 0;
            foreach ($row as $j => $value) {
                $weightedSum[$i] += $value * $weights[$j];
            }
        }

        // Consistency vector
        $consistencyVector = [];
        foreach ($weightedSum as $i => $ws) {
            $consistencyVector[$i] = $ws / $weights[$i];
        }

        // λmax
        $lambdaMax = array_sum($consistencyVector) / $n;

        // CI
        $CI = ($lambdaMax - $n) / ($n - 1);

        // Random Index values (Saaty 1980)
        $RI = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];

        // Consistency Ratio
        $CR = $n > 2 ? $CI / $RI[$n - 1] : 0;

        Log::info('AHP Consistency Check:', [
            'lambda_max'   => $lambdaMax,
            'CI'           => $CI,
            'CR'           => $CR,
            'is_consistent'=> $CR < 0.1,
        ]);

        return $CR;
    }
}
