<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\PaymentMethod;
use App\Enums\TransactionStatus;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'service_id',
        'total_amount',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'payment_method' => PaymentMethod::class,
        'status' => TransactionStatus::class,
    ];

    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

     public function service()
    {
        return $this->belongsTo(Service::class);
    }


    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
