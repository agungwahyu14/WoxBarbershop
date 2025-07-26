<?php
// app/Enums/TransactionStatus.php
namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case PAID = 'paid';
    case FAILED = 'failed';
    case CONFIRMED = 'confirmed';
    case CANCELLED = 'cancelled';
    case SETTLEMENT = 'settlement';
    case EXPIRE = 'expire';

    public function label(): string
    {
        return match($this) {
            self::PENDING => 'Pending',
            self::PAID => 'Paid',
            self::FAILED => 'Failed',
            self::CONFIRMED => 'Confirmed',
            self::CANCELLED => 'Cancelled',
            self::SETTLEMENT => 'Settlement',
            self::EXPIRE => 'Expired',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::PAID => 'bg-green-100 text-green-800',
            self::FAILED => 'bg-red-100 text-red-800',
            self::CONFIRMED => 'bg-green-100 text-green-800',
            self::CANCELLED => 'bg-red-100 text-red-800',
            self::SETTLEMENT => 'bg-green-100 text-green-800',
            self::EXPIRE => 'bg-gray-100 text-gray-800',
        };
    }

    public function isPaid(): bool
    {
        return in_array($this, [self::PAID, self::CONFIRMED, self::SETTLEMENT]);
    }

    public function isFailed(): bool
    {
        return in_array($this, [self::FAILED, self::CANCELLED, self::EXPIRE]);
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}