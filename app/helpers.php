<?php

use App\Enums\TransactionType;
use Carbon\Carbon;

/**
 * parseDateMultiFormat
 *
 * @param  mixed $date_string
 * @return Carbon
 */
if (!function_exists('parseDateMultiFormat')) {
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
}

/**
 * @param string $transaction_type
 * @return string
 */
if (!function_exists('getTransactionTypeLabel')) {
    function getTransactionTypeLabel(string $transaction_type): string
    {
        return match ($transaction_type) {
            TransactionType::card->name => 'CB',
            TransactionType::collection->name => 'Prélèvement',
            TransactionType::wero->name => 'Wero',
            TransactionType::transfer->name => 'Virement',
            TransactionType::transfer_instant->name => 'Virement instantané',
            TransactionType::perma_transfer->name => 'Virement permanent',
            TransactionType::check->name => 'Remise chèque',
            TransactionType::withdrawal->name => 'Retrait',
            TransactionType::mortgage->name => 'Prêt',
            default => 'Autre',
        };
    }
}

/**
 * formatAmount
 *
 * @param  mixed $amount
 * @param  mixed $decimals
 * @return string
 */
if (!function_exists('formatAmount')) {
    function formatAmount(float $amount, $decimals = 2): string
    {
        return number_format($amount, $decimals, ',', ' ');
    }
}
