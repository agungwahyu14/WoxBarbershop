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
        return $this->hasMany(Transaction::class, 'order_id', 'id');
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

    /**
     * Check if booking is expired (booking date has passed and still pending)
     */
    public function isExpired(): bool
    {
        return $this->status === 'pending' && $this->date_time->isPast();
    }

    /**
     * Check if booking can be cancelled
     */
    public function canBeCancelled(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }

    /**
     * Check if booking payment is pending
     */
    public function isPaymentPending(): bool
    {
        // Check if any related transaction has pending status
        return $this->transactions()->where('transaction_status', 'pending')->exists();
    }

    /**
     * Cancel booking and related transactions
     */
    public function cancelWithTransactions(): bool
    {
        \DB::beginTransaction();
        
        try {
            // Update booking status only
            $this->status = 'cancelled';
            $this->save();
            
            // Update all related transactions
            $this->transactions()->update([
                'transaction_status' => 'cancel'
            ]);
            
            \DB::commit();
            return true;
            
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Failed to cancel booking with transactions', [
                'booking_id' => $this->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Scope to get only pending bookings
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope to get expired bookings
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'pending')
                    ->where('date_time', '<', now());
    }

    /**
     * Get formatted booking date time
     */
    public function getFormattedDateTimeAttribute(): string
    {
        return $this->date_time->format('d/m/Y H:i');
    }

    /**
     * Get booking status with badge style
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'confirmed' => '<span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800">Confirmed</span>',
            'in_progress' => '<span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800">In Progress</span>',
            'completed' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Completed</span>',
            'cancelled' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Cancelled</span>',
        ];

        return $badges[$this->status] ?? '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }

    /**
     * Get payment status with badge style (from latest transaction)
     */
    public function getPaymentStatusBadgeAttribute(): string
    {
        // Get latest transaction to determine payment status
        $latestTransaction = $this->transaction;
        $status = $latestTransaction ? $latestTransaction->transaction_status : 'none';
        
        $badges = [
            'pending' => '<span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-800">Pending</span>',
            'settlement' => '<span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800">Paid</span>',
            'expire' => '<span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-800">Expired</span>',
            'cancel' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Cancelled</span>',
            'none' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">No Transaction</span>',
        ];

        return $badges[$status] ?? '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Unknown</span>';
    }
}
