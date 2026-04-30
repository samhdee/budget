<?php

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
    return null;
}
