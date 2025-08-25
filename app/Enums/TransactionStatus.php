<?php

// app/Enums/TransactionStatus.php

namespace App\Enums;

enum TransactionStatus: string
{
    case PENDING = 'pending';
    case SUCCESS = 'success';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::SUCCESS => 'Success',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'bg-yellow-100 text-yellow-800',
            self::SUCCESS => 'bg-green-100 text-green-800',
        };
    }

    public function isPaid(): bool
    {
        return $this === self::SUCCESS;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }
}
