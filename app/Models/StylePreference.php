<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StylePreference extends Model
{
    use HasFactory;
    
    protected $table = 'style_preference';

    protected $fillable = ['nama'];

    public function hairstyles()
    {
        return $this->belongsToMany(Hairstyle::class, 'hairstyle_style_preference');
    }
}
