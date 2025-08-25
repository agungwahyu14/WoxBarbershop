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
        'bentuk_kepala',
        'tipe_rambut',
        'style_preference', // Added field for style preference
        'image',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scores()
    {
        return $this->hasMany(HairstyleScore::class);
    }
}
