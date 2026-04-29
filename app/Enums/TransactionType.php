<?php

namespace App\Enums;

enum TransactionType: string
{
    case card = 'Carte';
    case transfer = 'Virement';
    case perma_transfer = 'Virement permanent';
    case wero = 'WERO';
    case collection = 'Prélèvement SEPA';
    case withdrawal = 'Retrait';
    case other = 'Autre';
}
