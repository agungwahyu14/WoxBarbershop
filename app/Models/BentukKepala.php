<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BentukKepala extends Model
{
    use HasFactory;
    
    protected $table = 'bentuk_kepala';

    protected $fillable = ['nama'];

    public function hairstyles()
    {
        return $this->belongsToMany(Hairstyle::class, 'hairstyle_bentuk_kepala');
    }
}
