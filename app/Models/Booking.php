<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    /**
     * Booking status constants
     */
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CANCELLED = 'cancelled';
    const STATUS_EXPIRED = 'expired';

    /**
     * Shift constants
     */
    const SHIFT_MORNING = 'morning';
    const SHIFT_AFTERNOON = 'afternoon';

    /**
     * Shift capacity in minutes
     */
    const SHIFT_MORNING_CAPACITY = 240; // 11:00 - 15:00 (4 hours)
    const SHIFT_AFTERNOON_CAPACITY = 360; // 16:00 - 22:00 (6 hours)

    /**
     * Shift time boundaries
     */
    const SHIFT_MORNING_START = '11:00';
    const SHIFT_MORNING_END = '15:00';
    const SHIFT_AFTERNOON_START = '16:00';
    const SHIFT_AFTERNOON_END = '22:00';

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
        'shift',
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
     * Scope to get expired bookings (older than 24 hours)
     */
    public function scopeExpired($query)
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_CANCELLED])
                    ->where('date_time', '<', now()->subHours(24));
    }

    /**
     * Scope to get bookings by shift
     */
    public function scopeByShift($query, $shift)
    {
        return $query->where('shift', $shift);
    }

    /**
     * Scope to get bookings by date
     */
    public function scopeByDate($query, $date)
    {
        return $query->whereDate('date_time', $date);
    }

    /**
     * Scope to get active bookings (exclude cancelled and expired)
     */
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_CANCELLED, self::STATUS_EXPIRED]);
    }

    /**
     * Calculate total booked duration for a specific date and shift
     */
    public static function getTotalBookedDuration($date, $shift)
    {
        return self::with('service')
            ->byDate($date)
            ->byShift($shift)
            ->active()
            ->get()
            ->sum(function ($booking) {
                $duration = $booking->service ? $booking->service->duration : '60';
                return (int) filter_var($duration, FILTER_SANITIZE_NUMBER_INT);
            });
    }

    /**
     * Get available capacity for a specific date and shift
     */
    public static function getAvailableCapacity($date, $shift)
    {
        $capacity = $shift === self::SHIFT_MORNING 
            ? self::SHIFT_MORNING_CAPACITY 
            : self::SHIFT_AFTERNOON_CAPACITY;
        
        $bookedDuration = self::getTotalBookedDuration($date, $shift);
        
        return max(0, $capacity - $bookedDuration);
    }

    /**
     * Check if a shift has capacity for a new booking
     */
    public static function hasCapacity($date, $shift, $requiredDuration)
    {
        $availableCapacity = self::getAvailableCapacity($date, $shift);
        return $availableCapacity >= $requiredDuration;
    }

    /**
     * Determine the shift based on time or auto-assign based on capacity
     */
    public static function determineShift($date, $serviceDuration)
    {
        // Check morning shift first
        if (self::hasCapacity($date, self::SHIFT_MORNING, $serviceDuration)) {
            return self::SHIFT_MORNING;
        }
        
        // Check afternoon shift
        if (self::hasCapacity($date, self::SHIFT_AFTERNOON, $serviceDuration)) {
            return self::SHIFT_AFTERNOON;
        }
        
        // No capacity available
        return null;
    }

    /**
     * Get shift time range
     */
    public static function getShiftTimeRange($shift)
    {
        if ($shift === self::SHIFT_MORNING) {
            return [
                'start' => self::SHIFT_MORNING_START,
                'end' => self::SHIFT_MORNING_END,
            ];
        }
        
        return [
            'start' => self::SHIFT_AFTERNOON_START,
            'end' => self::SHIFT_AFTERNOON_END,
        ];
    }

    /**
     * Scope to get bookings ready to cancel (passed but < 24 hours)
     */
    public function scopeReadyToCancel($query)
    {
        return $query->where('status', self::STATUS_PENDING)
                    ->where('date_time', '<', now())
                    ->where('date_time', '>=', now()->subHours(24));
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
            'expired' => '<span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800">Expired</span>',
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
