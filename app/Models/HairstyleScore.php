<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairstyleScore extends Model
{
    use HasFactory;

    protected $fillable = ['hairstyle_id', 'criterion_id', 'sub_criterion_id', 'score'];

    public function hairstyle()
    {
        return $this->belongsTo(Hairstyle::class, 'hairstyle_id');
    }

    public function criterion()
    {
        // foreign key = criterion_id, owner key = id
        return $this->belongsTo(Criteria::class, 'criterion_id', 'id');
    }

    /**
     * Get sub criterion berdasarkan criterion_id
     * - Jika criterion_id = 8 → BentukKepala
     * - Jika criterion_id = 9 → TipeRambut
     * - Jika criterion_id = 10 → StylePreference
     */
    public function getSubCriterionAttribute()
    {
        if ($this->criterion_id == 8) {
            return BentukKepala::find($this->sub_criterion_id);
        } elseif ($this->criterion_id == 9) {
            return TipeRambut::find($this->sub_criterion_id);
        } elseif ($this->criterion_id == 10) {
            return StylePreference::find($this->sub_criterion_id);
        }
        return null;
    }
}
