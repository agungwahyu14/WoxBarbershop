<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loyalty extends Model
{
    protected $fillable = [
        'user_id', 
        'points'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Add points for completed haircut
     */
    public function addPoints($points = 1)
    {
        $this->points += $points;
        $this->save();
    }

    /**
     * Check if customer can redeem free service (10 points)
     */
    public function canRedeemFreeService()
    {
        return $this->points >= 10;
    }

    /**
     * Redeem free service and reset points
     */
    public function redeemFreeService()
    {
        if ($this->canRedeemFreeService()) {
            $this->points = 0; // Reset to 0 after redemption
            $this->save();
            return true;
        }
        return false;
    }

    /**
     * Get progress percentage towards free service
     */
    public function getProgressPercentage()
    {
        return min(($this->points / 10) * 100, 100);
    }
}
