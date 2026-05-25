<?php

namespace App\Enums;

enum TransactionType: string
{
    case card = 'card';
    case wero = 'wero';
    case transfer = 'transfer';
    case transfer_instant = 'transfer_instant';
    case perma_transfer = 'perma_transfer';
    case collection = 'collection';
    case withdrawal = 'withdrawal';
    case mortgage = 'mortgage';
    case other = 'other';
}
