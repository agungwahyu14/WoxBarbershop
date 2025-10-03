<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'transaction_status',
        'payment_type',
        'gross_amount',
        'transaction_time',
        'bank',
        'va_number',
        'name',
        'email',
    ];

    /**
     * Relasi ke Booking menggunakan order_id sebagai foreign key
     * order_id di transaction = id di booking
     */
    public function booking()
    {
        return $this->belongsTo(\App\Models\Booking::class, 'order_id', 'id');
    }
}
