<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HairstyleScore extends Model
{
    use HasFactory;

    protected $fillable = ['hairstyle_id', 'criterion_id ', 'score'];


    public function hairstyle()
{
    return $this->belongsTo(Hairstyle::class);
}

public function criterion()
{
    return $this->belongsTo(Criteria::class);
}

}
