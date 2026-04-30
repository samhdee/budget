<?php

namespace App\Enums;

enum TransactionType: string
{
    case card = 'CB';
    case wero = 'Wero';
    case transfer = 'Virement';
    case perma_transfer = 'Virement permanent';
    case collection = 'Prélèvement SEPA';
    case withdrawal = 'Retrait';
    case other = 'Autre';
}
