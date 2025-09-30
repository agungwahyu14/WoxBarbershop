<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criteria'; // ← Ini yang penting

     protected $fillable = [
        'name',     // nama kriteria (contoh: Bentuk Kepala, Tipe Rambut)
        'weight'    // hasil bobot dari perhitungan AHP
    ];
public function scores()
{
    return $this->hasMany(HairstyleScore::class, 'criterion_id', 'id');
}

    public function comparisonsFrom()
    {
        return $this->hasMany(PairwiseComparison::class, 'criterion_id_1');
    }

    public function comparisonsTo()
    {
        return $this->hasMany(PairwiseComparison::class, 'criterion_id_2');
    }
}
