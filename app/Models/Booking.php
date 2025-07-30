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
    'status',
    'total_price',
    'payment_status',
    'order_id',
    'snap_token',
    'payment_expired_at',
    'midtrans_response',
];


    protected $casts = [
        'date_time' => 'datetime',
        'payment_expired_at' => 'datetime',
        'midtrans_response' => 'array',
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

}
