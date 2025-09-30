<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairstyleScore extends Model
{
    use HasFactory;

    protected $fillable = ['hairstyle_id', 'criterion_id', 'score'];

    public function hairstyle()
    {
        return $this->belongsTo(Hairstyle::class, 'hairstyle_id');
    }

    public function criterion()
    {
        // foreign key = criterion_id, owner key = id
        return $this->belongsTo(Criteria::class, 'criterion_id', 'id');
    }
}
