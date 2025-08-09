<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PairwiseComparison;
use App\Models\Criteria;

class PairwiseComparisonSeeder extends Seeder
{
    public function run(): void
{
    $criterias = Criteria::all()->keyBy('name');

    $data = [
        ['Bentuk Kepala', 'Tipe Rambut', 3],
        ['Bentuk Kepala', 'Preferensi Gaya', 5],
        ['Tipe Rambut', 'Preferensi Gaya', 2],
    ];

    foreach ($data as [$c1, $c2, $value]) {
        $id1 = $criterias[$c1]->id;
        $id2 = $criterias[$c2]->id;

        PairwiseComparison::updateOrCreate([
            'criterion_id_1' => $id1,
            'criterion_id_2' => $id2,
        ], [
            'value' => $value,
        ]);

        PairwiseComparison::updateOrCreate([
            'criterion_id_1' => $id2,
            'criterion_id_2' => $id1,
        ], [
            'value' => 1 / $value,
        ]);
    }

    foreach ($criterias as $criteria) {
        PairwiseComparison::updateOrCreate([
            'criterion_id_1' => $criteria->id,
            'criterion_id_2' => $criteria->id,
        ], [
            'value' => 1,
        ]);
    }
}

}
