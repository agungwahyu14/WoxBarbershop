<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loyalty extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'points',
        'total_earned',
        'total_redeemed',
        'last_transaction_date',
        'tier',
        'tier_benefits'
    ];

    protected $casts = [
        'last_transaction_date' => 'date',
        'tier_benefits' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function addPoints(int $points): void
    {
        $this->points += $points;
        $this->total_earned += $points;
        $this->last_transaction_date = now();
        $this->updateTier();
        $this->save();
    }

    public function redeemPoints(int $points): bool
    {
        if ($this->points >= $points) {
            $this->points -= $points;
            $this->total_redeemed += $points;
            $this->save();
            return true;
        }
        return false;
    }

    private function updateTier(): void
    {
        $totalEarned = $this->total_earned;
        
        if ($totalEarned >= 1000) {
            $this->tier = 'platinum';
            $this->tier_benefits = [
                'discount_percentage' => 15,
                'free_services' => 2,
                'priority_booking' => true
            ];
        } elseif ($totalEarned >= 500) {
            $this->tier = 'gold';
            $this->tier_benefits = [
                'discount_percentage' => 10,
                'free_services' => 1,
                'priority_booking' => true
            ];
        } elseif ($totalEarned >= 250) {
            $this->tier = 'silver';
            $this->tier_benefits = [
                'discount_percentage' => 5,
                'free_services' => 0,
                'priority_booking' => false
            ];
        } else {
            $this->tier = 'bronze';
            $this->tier_benefits = [
                'discount_percentage' => 0,
                'free_services' => 0,
                'priority_booking' => false
            ];
        }
    }
}