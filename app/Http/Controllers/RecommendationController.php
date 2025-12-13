<?php

namespace App\Http\Controllers;

use App\Models\Criteria;
use App\Models\Hairstyle;
use App\Models\PairwiseComparison;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * RecommendationController
 * 
 * Controller untuk sistem rekomendasi hairstyle menggunakan metode AHP (Analytical Hierarchy Process).
 * 
 * ═══════════════════════════════════════════════════════════════════════════════════
 * FLOW PERHITUNGAN AHP
 * ═══════════════════════════════════════════════════════════════════════════════════
 * 
 * 1. Mengambil semua kriteria dari database (bentuk kepala, tipe rambut, preferensi gaya)
 * 2. Membangun matriks perbandingan berpasangan (pairwise comparison) antar kriteria
 * 3. Menormalisasi matriks dan menghitung bobot (weight) untuk setiap kriteria
 * 4. Menghitung Consistency Ratio (CR) untuk memvalidasi konsistensi penilaian
 * 5. Mengalikan bobot kriteria dengan skor hairstyle untuk mendapatkan skor akhir
 * 6. Mengurutkan hairstyle berdasarkan skor tertinggi sebagai rekomendasi
 * 
 * ═══════════════════════════════════════════════════════════════════════════════════
 * CONTOH PERHITUNGAN (Berdasarkan Data Aktual dari Database)
 * ═══════════════════════════════════════════════════════════════════════════════════
 * 
 * DATA KRITERIA (dari CriteriasTableSeeder.php):
 * - Bentuk Kepala (ID akan auto-increment, misal ID 1)
 * - Tipe Rambut (misal ID 2)
 * - Preferensi Gaya (misal ID 3)
 * 
 * PAIRWISE COMPARISON (dari PairwiseComparisonSeeder.php):
 * - Bentuk Kepala vs Tipe Rambut       = 1.67  (Bentuk Kepala 1.67x lebih penting)
 * - Bentuk Kepala vs Preferensi Gaya   = 2.50  (Bentuk Kepala 2.50x lebih penting)
 * - Tipe Rambut vs Preferensi Gaya     = 1.50  (Tipe Rambut 1.50x lebih penting)
 * 
 * MATRIKS PERBANDINGAN (3×3):
 * ┌────────────────────────────────────────────────────────────────────────┐
 * │                │  Bentuk   │   Tipe    │ Preferensi │  Normalized │  Weight │
 * │                │  Kepala   │  Rambut   │    Gaya    │   Average   │         │
 * ├────────────────────────────────────────────────────────────────────────┤
 * │ Bentuk Kepala  │   1.00    │   1.67    │    2.50    │   0.5003    │ 0.5003  │
 * │ Tipe Rambut    │   0.60    │   1.00    │    1.50    │   0.2998    │ 0.2998  │
 * │ Preferensi Gaya│   0.40    │   0.67    │    1.00    │   0.1999    │ 0.1999  │
 * ├────────────────────────────────────────────────────────────────────────┤
 * │ Column Sum     │   2.00    │   3.34    │    5.00    │   1.0000    │         │
 * └────────────────────────────────────────────────────────────────────────┘
 * 
 * PERHITUNGAN NORMALISASI:
 * Bentuk Kepala:
 *   = (1.00/2.00 + 1.67/3.34 + 2.50/5.00) / 3
 *   = (0.5000 + 0.5000 + 0.5000) / 3
 *   = 1.5000 / 3 = 0.5000 ≈ 50.00%
 * 
 * Tipe Rambut:
 *   = (0.60/2.00 + 1.00/3.34 + 1.50/5.00) / 3
 *   = (0.3000 + 0.2994 + 0.3000) / 3
 *   = 0.8994 / 3 = 0.2998 ≈ 29.98%
 * 
 * Preferensi Gaya:
 *   = (0.40/2.00 + 0.67/3.34 + 1.00/5.00) / 3
 *   = (0.2000 + 0.2006 + 0.2000) / 3
 *   = 0.6006 / 3 = 0.2002 ≈ 20.02%
 * 
 * CONSISTENCY RATIO: CR ≈ 0.0009 < 0.1 ✅ (KONSISTEN)
 * 
 * ─────────────────────────────────────────────────────────────────────────
 * CONTOH PERHITUNGAN SCORE HAIRSTYLE:
 * ─────────────────────────────────────────────────────────────────────────
 * 
 * User Input:
 *   - Bentuk Kepala: "Square" (Persegi)
 *   - Tipe Rambut: "Lurus"
 *   - Preferensi: "Modern"
 * 
 * Hairstyle: "French Crop" (dari HairstyleSeeder.php)
 * Deskripsi: Potongan pendek dengan bagian depan yang dipotong lurus dan texture alami
 * 
 * Asumsi Scores dari database (hairstyle_scores table):
 *   - Bentuk Kepala (Square): 9/10 (cocok untuk bentuk persegi)
 *   - Tipe Rambut (Lurus): 9/10 (sangat cocok untuk rambut lurus)
 *   - Preferensi Gaya (Modern): 10/10 (style modern minimalis)
 * 
 * Perhitungan Total Score dengan Bobot AHP:
 *   Total Score = (W_BentukKepala × Score_BentukKepala) + 
 *                 (W_TipeRambut × Score_TipeRambut) + 
 *                 (W_PreferensiGaya × Score_PreferensiGaya)
 * 
 *   = (0.5000 × 9) + (0.2998 × 9) + (0.2002 × 10)
 *   = 4.5000 + 2.6982 + 2.0020
 *   = 9.2002
 * 
 * Breakdown Kontribusi per Kriteria:
 *   - Bentuk Kepala:    4.5000  (48.91% dari total score)
 *   - Tipe Rambut:      2.6982  (29.33% dari total score)
 *   - Preferensi Gaya:  2.0020  (21.76% dari total score)
 * 
 * Hasil: French Crop mendapat score 9.20/10 ⭐ (HIGHLY RECOMMENDED!)
 * 
 * INTERPRETASI:
 * - Bentuk Kepala memberikan kontribusi terbesar (50%) sesuai dengan bobot AHP
 * - Distribusi bobot lebih seimbang dibanding pairwise comparison yang lebih ekstrem
 * - French Crop sangat direkomendasikan untuk user dengan kriteria ini
 * - Score tinggi menandakan kesesuaian yang sangat baik dengan preferensi user
 * 
 * ═══════════════════════════════════════════════════════════════════════════════════
 * 
 * Metode AHP memastikan bobot kriteria dihitung secara objektif dan terukur
 * berdasarkan tingkat kepentingan relatif antar kriteria.
 * 
 * Untuk dokumentasi lengkap, lihat file: DOKUMENTASI_AHP.md
 */
class RecommendationController extends Controller
{
    /**
     * Halaman utama sistem rekomendasi hairstyle
     * 
     * Flow Lengkap:
     * 1. Input Filtering: Menerima filter dari user (bentuk kepala, tipe rambut, preferensi gaya)
     * 2. AHP Calculation: Menghitung bobot kriteria menggunakan metode AHP
     * 3. Hairstyle Filtering: Mengambil hairstyle yang sesuai dengan filter user
     * 4. Sub-Criterion Mapping: Mapping input user ke sub_criterion_id yang sesuai
     *    - PENTING: Setiap hairstyle memiliki multiple scores per criterion
     *    - Contoh: Hairstyle "Pompadour" punya 6 scores untuk "Bentuk Kepala" 
     *      (satu untuk Oval, Bulat, Persegi, dll)
     *    - Kita harus memilih score yang sesuai dengan input user
     * 5. Score Calculation: Menghitung skor total untuk setiap hairstyle
     *    - Skor Total = Σ(Bobot Kriteria × Skor Hairstyle untuk Sub-Kriteria User)
     *    - Formula: Total = (W_BentukKepala × Score_UserBentukKepala) + 
     *                       (W_TipeRambut × Score_UserTipeRambut) + 
     *                       (W_PreferensiGaya × Score_UserPreferensiGaya)
     * 6. Ranking: Mengurutkan hairstyle dari skor tertinggi ke terendah
     * 7. Output: Menampilkan hasil rekomendasi beserta CR (Consistency Ratio)
     * 
     * @param Request $request - Filter input: bentuk_kepala, tipe_rambut, preferensi_gaya
     * @return \Illuminate\View\View - View rekomendasi dengan hasil dan CR
     */
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

        // Step 3: Mapping input user ke sub_criterion_id
        $subCriterionIds = $this->mapUserInputToSubCriterionIds($bentukKepala, $tipeRambut, $preferensiGaya);
        Log::info('Sub-criterion IDs based on user input:', $subCriterionIds);

        // Step 4: Hitung skor rekomendasi
        $results = [];
        foreach ($hairstyles as $hairstyle) {
            $totalScore = 0;
            $logDetail  = [];

            // Loop untuk setiap criterion (bukan setiap score!)
            foreach ($weights as $criterionId => $weight) {
                // Ambil sub_criterion_id yang sesuai dengan input user
                $subCriterionId = $subCriterionIds[$criterionId] ?? null;

                if (!$subCriterionId) {
                    // Jika user tidak input kriteria ini, skip atau gunakan default
                    Log::warning("No sub-criterion ID for criterion $criterionId, skipping for hairstyle {$hairstyle->id}");
                    continue;
                }

                // Ambil score yang spesifik untuk criterion dan sub_criterion ini
                $score = $hairstyle->scores()
                    ->where('criterion_id', $criterionId)
                    ->where('sub_criterion_id', $subCriterionId)
                    ->first();

                if (!$score) {
                    Log::warning("No score found for hairstyle {$hairstyle->id}, criterion $criterionId, sub_criterion $subCriterionId");
                    continue;
                }

                $contribution = $weight * $score->score;
                $totalScore += $contribution;

                $logDetail[] = [
                    'criterion_id'     => $criterionId,
                    'sub_criterion_id' => $subCriterionId,
                    'weight'           => $weight,
                    'score'            => $score->score,
                    'contribution'     => round($contribution, 4),
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

    /**
     * Mapping input user ke sub_criterion_id
     * 
     * Fungsi ini mengkonversi input user (nama bentuk kepala, tipe rambut, preferensi gaya)
     * menjadi sub_criterion_id yang sesuai untuk digunakan dalam perhitungan score.
     * 
     * DINAMIS: Criterion ID diambil dari database berdasarkan nama kriteria,
     * sehingga tidak perlu hardcode ID dan lebih fleksibel terhadap perubahan data.
     * 
     * @param string|null $bentukKepala - Nama bentuk kepala (contoh: "Oval", "Bulat")
     * @param string|null $tipeRambut - Nama tipe rambut (contoh: "Lurus", "Keriting")
     * @param string|null $preferensiGaya - Nama preferensi gaya (contoh: "Formal", "Casual")
     * @return array - Array dengan key criterion_id dan value sub_criterion_id
     */
    private function mapUserInputToSubCriterionIds($bentukKepala, $tipeRambut, $preferensiGaya)
    {
        $result = [];

        // Ambil Criterion ID secara dinamis dari database berdasarkan nama
        $criterionBentukKepala = Criteria::where('name', 'Bentuk Kepala')->first();
        $criterionTipeRambut = Criteria::where('name', 'Tipe Rambut')->first();
        $criterionPreferensiGaya = Criteria::where('name', 'Preferensi Gaya')->first();

        // Mapping Bentuk Kepala
        if ($bentukKepala && $criterionBentukKepala) {
            $bentuk = \App\Models\BentukKepala::where('nama', $bentukKepala)->first();
            if ($bentuk) {
                $result[$criterionBentukKepala->id] = $bentuk->id;
                Log::info("Mapped Bentuk Kepala: {$bentukKepala} → Criterion ID: {$criterionBentukKepala->id}, Sub-Criterion ID: {$bentuk->id}");
            }
        }

        // Mapping Tipe Rambut
        if ($tipeRambut && $criterionTipeRambut) {
            $tipe = \App\Models\TipeRambut::where('nama', $tipeRambut)->first();
            if ($tipe) {
                $result[$criterionTipeRambut->id] = $tipe->id;
                Log::info("Mapped Tipe Rambut: {$tipeRambut} → Criterion ID: {$criterionTipeRambut->id}, Sub-Criterion ID: {$tipe->id}");
            }
        }

        // Mapping Preferensi Gaya
        if ($preferensiGaya && $criterionPreferensiGaya) {
            $preferensi = \App\Models\StylePreference::where('nama', $preferensiGaya)->first();
            if ($preferensi) {
                $result[$criterionPreferensiGaya->id] = $preferensi->id;
                Log::info("Mapped Preferensi Gaya: {$preferensiGaya} → Criterion ID: {$criterionPreferensiGaya->id}, Sub-Criterion ID: {$preferensi->id}");
            }
        }

        return $result;
    }

    /**
     * Menghitung bobot kriteria menggunakan metode AHP (Analytical Hierarchy Process)
     * 
     * ═════════════════════════════════════════════════════════════════════════════
     * FLOW PERHITUNGAN AHP
     * ═════════════════════════════════════════════════════════════════════════════
     * 
     * 1. INISIALISASI
     *    - Mengambil semua kriteria dari database
     *    - Mengambil data pairwise comparison dari database
     * 
     * 2. MEMBANGUN MATRIKS PERBANDINGAN BERPASANGAN (n×n)
     *    - Diagonal matriks = 1 (kriteria dibandingkan dengan dirinya sendiri)
     *    - Elemen (i,j) = nilai perbandingan kriteria i terhadap j
     *    - Elemen (j,i) = 1/nilai(i,j) (reciprocal/kebalikan)
     * 
     * 3. NORMALISASI MATRIKS
     *    a. Hitung jumlah setiap kolom (column sum)
     *    b. Bagi setiap elemen dengan jumlah kolomnya
     *    c. Hitung rata-rata setiap baris → ini adalah BOBOT (weight) kriteria
     *    Formula: weight[i] = (Σ normalized_matrix[i][j]) / n
     * 
     * 4. MENYIMPAN BOBOT KE DATABASE
     *    - Update kolom 'weight' di tabel criteria
     * 
     * 5. VALIDASI KONSISTENSI
     *    - Hitung Consistency Ratio (CR) menggunakan calculateConsistencyRatio()
     *    - CR < 0.1 dianggap konsisten dan dapat diterima
     *    - CR ≥ 0.1 menandakan inkonsistensi dalam penilaian pairwise comparison
     * 
     * ═════════════════════════════════════════════════════════════════════════════
     * CONTOH PERHITUNGAN DENGAN DATA AKTUAL
     * ═════════════════════════════════════════════════════════════════════════════
     * 
     * Input Pairwise Comparison:
     *   Bentuk Kepala (8) vs Tipe Rambut (9)       = 1.67
     *   Bentuk Kepala (8) vs Preferensi Gaya (10)  = 2.5
     *   Tipe Rambut (9) vs Preferensi Gaya (10)    = 1.5
     * 
     * STEP 1: Build Matrix (3×3)
     * ┌─────────────────────────────────────────────────────┐
     * │          │  C8 (Bentuk) │  C9 (Tipe) │ C10 (Pref) │
     * ├─────────────────────────────────────────────────────┤
     * │ C8       │     1.00     │    1.67    │    2.50    │
     * │ C9       │     0.60     │    1.00    │    1.50    │
     * │ C10      │     0.40     │    0.67    │    1.00    │
     * ├─────────────────────────────────────────────────────┤
     * │ Col Sum  │     2.00     │    3.34    │    5.00    │
     * └─────────────────────────────────────────────────────┘
     * 
     * Note: C9 vs C8 = 1/1.67 = 0.60 (reciprocal)
     * 
     * STEP 2: Normalize Matrix
     * ┌─────────────────────────────────────────────────────────────┐
     * │          │     C8      │     C9      │     C10     │  Avg   │
     * ├─────────────────────────────────────────────────────────────┤
     * │ C8       │  1.00/2.00  │  1.67/3.34  │  2.50/5.00  │ 0.5003 │
     * │          │  = 0.500    │  = 0.500    │  = 0.500    │        │
     * │ C9       │  0.60/2.00  │  1.00/3.34  │  1.50/5.00  │ 0.2998 │
     * │          │  = 0.300    │  = 0.299    │  = 0.300    │        │
     * │ C10      │  0.40/2.00  │  0.67/3.34  │  1.00/5.00  │ 0.2000 │
     * │          │  = 0.200    │  = 0.201    │  = 0.200    │        │
     * └─────────────────────────────────────────────────────────────┘
     * 
     * STEP 3: Calculate Weights
     *   W(C8)  = (0.500 + 0.500 + 0.500) / 3 = 0.500266 (50.03%)
     *   W(C9)  = (0.300 + 0.299 + 0.300) / 3 = 0.299760 (29.98%)
     *   W(C10) = (0.200 + 0.201 + 0.200) / 3 = 0.199973 (19.99%)
     *   Total  = 1.000 ✅
     * 
     * STEP 4: Consistency Check
     *   CR = 0.00086 < 0.1 ✅ (KONSISTEN)
     * 
     * Hasil: Bentuk Kepala adalah kriteria TERPENTING (50.03%)
     * ═════════════════════════════════════════════════════════════════════════════
     * 
     * @return array [$weights, $CR] - Array berisi bobot setiap kriteria dan Consistency Ratio
     */
    private function calculateAHPWeights()
    {
        $criterias   = Criteria::all();
        $criteriaIds = $criterias->pluck('id')->toArray();
        $n           = count($criterias);
        Log::info("Calculating AHP weights for $n criteria.");

        // Ambil semua pairwise comparison sekaligus
        $comparisons = PairwiseComparison::all()->keyBy(fn ($c) => $c->criterion_id_1.'-'.$c->criterion_id_2);

        // STEP 2: Bangun matriks perbandingan berpasangan (n×n)
        // Matriks ini merepresentasikan tingkat kepentingan relatif antar kriteria
        $matrix = [];
foreach ($criteriaIds as $i) {
    foreach ($criteriaIds as $j) {
        if ($i === $j) {
            $matrix[$i][$j] = 1; // diagonal selalu 1 (kriteria sama dengan dirinya sendiri)
        } elseif (isset($comparisons[$i.'-'.$j])) {
            // Gunakan nilai perbandingan langsung jika tersedia
            $matrix[$i][$j] = $comparisons[$i.'-'.$j]->value;
        } elseif (isset($comparisons[$j.'-'.$i])) {
            // Jika data kebalikan tersedia, gunakan reciprocal (1/nilai)
            // Contoh: Jika B vs A = 3, maka A vs B = 1/3
            $matrix[$i][$j] = 1 / $comparisons[$j.'-'.$i]->value;
        } else {
            // Default: kriteria dianggap sama pentingnya
            $matrix[$i][$j] = 1;
        }
    }
}

        // STEP 3a: Hitung jumlah setiap kolom (column sum)
        // Ini adalah langkah pertama dalam normalisasi matriks
        $columnSums = [];
        foreach ($criteriaIds as $j) {
            $columnSums[$j] = 0;
            foreach ($criteriaIds as $i) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // STEP 3b & 3c: Normalisasi matriks dan hitung bobot (weight)
        // Bobot = rata-rata dari baris yang sudah dinormalisasi
        $weights = [];
        foreach ($criteriaIds as $i) {
            $weights[$i] = 0;
            foreach ($criteriaIds as $j) {
                // Normalisasi: bagi setiap elemen dengan jumlah kolomnya
                $weights[$i] += $matrix[$i][$j] / $columnSums[$j];
            }
            // Hitung rata-rata baris untuk mendapatkan bobot akhir
            $weights[$i] /= $n;

            // STEP 4: Simpan bobot ke database untuk digunakan dalam perhitungan skor
            Criteria::where('id', $i)->update(['weight' => $weights[$i]]);
        }

        // STEP 5: Hitung Consistency Ratio untuk validasi
        // CR < 0.1 = konsisten (dapat diterima)
        // CR ≥ 0.1 = inkonsisten (perlu review pairwise comparison)
        $CR = $this->calculateConsistencyRatio($matrix, $weights, $n);

        Log::info("AHP Weights updated in DB: ".json_encode($weights));
        Log::info("AHP Consistency Ratio: ".$CR);

        return [$weights, $CR];
    }

    /**
     * Menghitung Consistency Ratio (CR) untuk validasi konsistensi penilaian AHP
     * 
     * Flow Perhitungan CR:
     * 
     * 1. WEIGHTED SUM VECTOR
     *    Untuk setiap baris matriks, kalikan dengan bobot dan jumlahkan
     *    weighted_sum[i] = Σ(matrix[i][j] × weight[j])
     * 
     * 2. CONSISTENCY VECTOR
     *    Bagi weighted sum dengan bobot kriteria
     *    consistency_vector[i] = weighted_sum[i] / weight[i]
     * 
     * 3. LAMBDA MAX (λmax)
     *    Rata-rata dari consistency vector
     *    λmax = Σ(consistency_vector[i]) / n
     * 
     * 4. CONSISTENCY INDEX (CI)
     *    Ukuran penyimpangan dari konsistensi sempurna
     *    CI = (λmax - n) / (n - 1)
     * 
     * 5. CONSISTENCY RATIO (CR)
     *    Perbandingan CI dengan Random Index (RI) dari tabel Saaty
     *    CR = CI / RI[n]
     * 
     * Interpretasi CR:
     * - CR < 0.1  : Konsisten, penilaian dapat diterima
     * - CR ≥ 0.1  : Inkonsisten, perlu review pairwise comparison
     * 
     * @param array $matrix - Matriks perbandingan berpasangan
     * @param array $weights - Bobot yang telah dihitung
     * @param int $n - Jumlah kriteria
     * @return float - Nilai Consistency Ratio (0 = konsisten sempurna)
     */
    private function calculateConsistencyRatio($matrix, $weights, $n)
    {
        // STEP 1: Hitung weighted sum vector
        $weightedSum = [];
        foreach ($matrix as $i => $row) {
            $weightedSum[$i] = 0;
            foreach ($row as $j => $value) {
                $weightedSum[$i] += $value * $weights[$j];
            }
        }

        // STEP 2: Hitung consistency vector
        $consistencyVector = [];
        foreach ($weightedSum as $i => $ws) {
            $consistencyVector[$i] = $ws / $weights[$i];
        }

        // STEP 3: Hitung λmax (lambda max)
        $lambdaMax = array_sum($consistencyVector) / $n;

        // STEP 4: Hitung CI (Consistency Index)
        // Semakin kecil CI, semakin konsisten penilaiannya
        $CI = ($lambdaMax - $n) / ($n - 1);

        // Random Index values (Saaty 1980)
        // Nilai RI untuk n kriteria, digunakan sebagai baseline konsistensi random
        $RI = [0, 0, 0.58, 0.90, 1.12, 1.24, 1.32, 1.41, 1.45, 1.49];

        // STEP 5: Hitung CR (Consistency Ratio)
        // CR = CI / RI → Membandingkan konsistensi aktual dengan konsistensi random
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
