<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'service_id',
        'hairstyle_id',
        'date_time',
        'queue_number',
        'description',
        'payment_method',
        'status',
        'total_price',

    ];

    protected $casts = [
        'date_time' => 'datetime',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relasi ke Hairstyle
    public function hairstyle()
    {
        return $this->belongsTo(Hairstyle::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class, 'booking_id');
    }

    // Single transaction (latest)
    public function transaction()
    {
        return $this->hasOne(Transaction::class, 'order_id', 'id')->latest();
    }

    // Relasi ke Feedback
    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }
}
