<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipeRambut extends Model
{
    use HasFactory;

    protected $table = 'tipe_rambut';

    protected $fillable = ['nama'];

    public function hairstyles()
    {
        return $this->belongsToMany(Hairstyle::class, 'hairstyle_tipe_rambut')
                    ->withPivot('score');
    }
}
