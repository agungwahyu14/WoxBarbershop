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
        'price',
        'payment_method',
        'payment_status',
    ];

    protected $casts = [
        'payment_method' => 'string',
        'payment_status' => 'string',
        'total_amount' => 'decimal:2',
        'price' => 'decimal:2',
    ];

    // Relasi ke Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Relasi ke Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor untuk format mata uang
    public function getFormattedTotalAmountAttribute()
    {
        return 'Rp ' . number_format($this->total_amount, 0, ',', '.');
    }

    // Accessor untuk format payment method
    public function getFormattedPaymentMethodAttribute()
    {
        return ucfirst(str_replace('_', ' ', $this->payment_method));
    }

    // Accessor untuk format payment status
    public function getFormattedPaymentStatusAttribute()
    {
        return ucfirst($this->payment_status);
    }

    // Scope untuk filter berdasarkan status
    public function scopeByStatus($query, $status)
    {
        return $query->where('payment_status', $status);
    }

    // Scope untuk filter berdasarkan metode pembayaran
    public function scopeByPaymentMethod($query, $method)
    {
        return $query->where('payment_method', $method);
    }

    // Scope untuk filter berdasarkan user
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // Method untuk mengecek apakah transaksi sudah dibayar
    public function isPaid()
    {
        return in_array($this->payment_status, ['paid', 'settlement', 'confirmed']);
    }

    // Method untuk mengecek apakah transaksi masih pending
    public function isPending()
    {
        return $this->payment_status === 'pending';
    }

    // Method untuk mengecek apakah transaksi gagal
    public function isFailed()
    {
        return in_array($this->payment_status, ['failed', 'cancelled', 'expire']);
    }
}