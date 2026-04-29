<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $occurred_at
 * @property numeric $amount
 * @property int $beneficiary_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $category_id
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereBeneficiaryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCategoryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereOccurredAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Transaction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'occurred_at',
        'amount',
        'beneficiary_id',
        'type',
        'line',
        'file',
    ];

    public function beneficiary(): HasOne
    {
        return $this->hasOne(Beneficiary::class, 'beneficiary_id');
    }

    /**
     * @return Collection
     */
    public static function getList(): Collection
    {
        return self::query()
            ->select([
                'transactions.id as transac_id', 'amount', 'occurred_at', 'type', 'line', 'file',
                'b.id as benef_id', 'raw_name', 'pretty_name'
            ])
            ->join('beneficiaries as b', 'b.id', 'transactions.beneficiary_id')
            ->orderByDesc('occurred_at')
            ->orderByDesc('line')
            ->get();
    }
}
