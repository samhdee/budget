<?php

namespace App\Enums;

enum TransactionType: string
{
    case card = 'card';
    case wero = 'wero';
    case transfer = 'transfer';
    case perma_transfer = 'perma_transfer';
    case collection = 'collection';
    case withdrawal = 'withdrawal';
    case other = 'other';
}
