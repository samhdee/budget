<?php

use App\Enums\TransactionType;
use Carbon\Carbon;

function parseDateMultiFormat(string $date_string): ?Carbon
{
    $formats = [
        'd/m/y',
        'd-m-y',
        'd/m/Y',
        'd/m',
        'd-m-Y',
        'Y-m-d',
    ];

    foreach ($formats as $format) {
        try {
            return Carbon::createFromFormat($format, $date_string);
        } catch (Exception $e) {
            // On continue
        }
    }

    // Aucun format valide
    dd("$date_string : Aucun format de date ne correspond !");
}

/**
 * @param $transaction_type
 * @return string
 */
function getTransactionTypeLabel($transaction_type): string
{
    return match ($transaction_type) {
        TransactionType::card->name => 'CB',
        TransactionType::collection->name => 'Prélèvement',
        TransactionType::wero->name => 'Wero',
        TransactionType::transfer->name => 'Virement',
        TransactionType::perma_transfer->name => 'Virement permanent',
        TransactionType::withdrawal->name => 'Retrait',
        TransactionType::mortgage->name => 'Prêt',
        default => 'Autre',
    };
}

function formatAmount($amount): string
{
    return number_format($amount, 2, ',', ' ');
}
