<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criteria extends Model
{
    use HasFactory;

    protected $table = 'criteria'; // ← Ini yang penting

    protected $fillable = ['name'];

    public function comparisonsFrom()
{
    return $this->hasMany(PairwiseComparison::class, 'criterion_id_1');
}

public function comparisonsTo()
{
    return $this->hasMany(PairwiseComparison::class, 'criterion_id_2');
}
    
}
