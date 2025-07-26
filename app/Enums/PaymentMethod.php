<?php
// app/Enums/PaymentMethod.php
namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case BANK_TRANSFER = 'bank_transfer';
    case E_WALLET = 'e_wallet';
    case CREDIT_CARD = 'credit_card';
    case QRIS = 'qris';
    case TRANSFER = 'transfer';

    public function label(): string
    {
        return match($this) {
            self::CASH => 'Cash',
            self::BANK_TRANSFER => 'Bank Transfer',
            self::E_WALLET => 'E-Wallet',
            self::CREDIT_CARD => 'Credit Card',
            self::QRIS => 'QRIS',
            self::TRANSFER => 'Transfer',
        };
    }
}