<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PairwiseComparison extends Model
{
    use HasFactory;

    public function criterion1()
    {
        return $this->belongsTo(Criteria::class, 'criterion_id_1');
    }

    public function criterion2()
    {
        return $this->belongsTo(Criteria::class, 'criterion_id_2');
    }
}
