<?php

namespace App\Enums;

enum TransactionType: string
{
    case card = 'Carte';
    case transfer = 'Virement';
    case collection = 'Prélèvement SEPA';
    case withdrawal = 'Retrait';
}
