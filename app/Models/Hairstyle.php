<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hairstyle extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'image',
    ];

    // Relasi ke bentuk kepala
   public function bentuk_kepala()
{
    return $this->belongsToMany(BentukKepala::class, 'hairstyle_bentuk_kepala');
}

public function tipe_rambut()
{
    return $this->belongsToMany(TipeRambut::class, 'hairstyle_tipe_rambut');
}

public function style_preference()
{
    return $this->belongsToMany(StylePreference::class, 'hairstyle_style_preference');
}

    // Relasi ke booking jika ada
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // // Relasi ke user jika diperlukan
    // public function user()
    // {
    //     return $this->belongsTo(User::class);
    // }

    // Relasi ke scores
    public function scores()
    {
        return $this->hasMany(HairstyleScore::class);
    }

    public function bentukKepala()
{
    return $this->belongsToMany(BentukKepala::class, 'hairstyle_bentuk_kepala')
                ->withPivot('score');
}

public function tipeRambut()
{
    return $this->belongsToMany(TipeRambut::class, 'hairstyle_tipe_rambut')
                ->withPivot('score');
}

public function stylePreference()
{
    return $this->belongsToMany(StylePreference::class, 'hairstyle_style_preference')
                ->withPivot('score');
}

}
