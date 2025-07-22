<?php
// app/Enums/PaymentMethod.php
namespace App\Enums;

enum PaymentMethod: string
{
    case CASH = 'cash';
    case QRIS = 'qris';
    case TRANSFER = 'transfer';
}
