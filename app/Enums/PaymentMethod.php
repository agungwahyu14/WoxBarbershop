<?php

// app/Enums/PaymentMethod.php

namespace App\Enums;

enum PaymentMethod: string
{
    case BANK_TRANSFER = 'bank_transfer';
    case BCA = 'echannel';
    case QRIS = 'qris';
    case GOPAY = 'gopay';
    case SHOPEEPAY = 'shopeepay';
    case CREDIT_CARD = 'credit_card';
    case AKULAKU = 'akulaku';
    case KREDIVO = 'kredivo';

    public function label(): string
    {
        return match ($this) {
            self::BANK_TRANSFER => 'Bank Transfer',
            self::BCA => 'BCA Virtual Account',
            self::QRIS => 'QRIS',
            self::GOPAY => 'GoPay',
            self::SHOPEEPAY => 'ShopeePay',
            self::CREDIT_CARD => 'Credit Card',
            self::AKULAKU => 'Akulaku',
            self::KREDIVO => 'Kredivo',
        };
    }
}
