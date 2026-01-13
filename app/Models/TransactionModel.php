<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperTransactionModel
 */
class TransactionModel extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'occurred_at',
        'amount',
    ];
}
