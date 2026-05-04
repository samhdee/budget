<?php

namespace App\Models;

use App\Enums\TransactionType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $occurred_at
 * @property numeric $amount
 * @property TransactionType $type
 * @property string $notes
 * @property int $beneficiary_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $category_id
 * @method static Builder<static>|Transaction newModelQuery()
 * @method static Builder<static>|Transaction newQuery()
 * @method static Builder<static>|Transaction query()
 * @method static Builder<static>|Transaction whereAmount($value)
 * @method static Builder<static>|Transaction whereType($value)
 * @method static Builder<static>|Transaction whereNotes($value)
 * @method static Builder<static>|Transaction whereBeneficiaryId($value)
 * @method static Builder<static>|Transaction whereCategoryId($value)
 * @method static Builder<static>|Transaction whereCreatedAt($value)
 * @method static Builder<static>|Transaction whereId($value)
 * @method static Builder<static>|Transaction whereOccurredAt($value)
 * @method static Builder<static>|Transaction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Transaction extends Model
{
    protected $table = 'transactions';
    protected $fillable = [
        'occurred_at',
        'amount',
        'notes',
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
     * @param array $filters
     * @return Collection
     */
    public static function getList(array $filters = []): Collection
    {
        $query = self::query()
            ->select([
                'transactions.id as id', 'amount', 'occurred_at', 'type', 'notes', 'line', 'file',
                'beneficiary_id', 'raw_name', 'pretty_name'
            ])
            ->join('beneficiaries as b', 'b.id', 'transactions.beneficiary_id');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['benef_name'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->orWhereLike('b.raw_name', "%{$filters['benef_name']}%")
                    ->orWhereLike('b.pretty_name', "%{$filters['benef_name']}%");
            });
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('occurred_at', '>=', $filters['date_start']);
        }

        if (!empty($filters['date_end'])) {
            $query->whereDate('occurred_at', '<=', $filters['date_end']);
        }

        return $query->orderByDesc('occurred_at')
            ->orderByDesc('line')
            ->get();
    }

    /**
     * @param int $id
     * @return Transaction
     */
    public static function getOne(int $id): Transaction
    {
        return self::query()
            ->select([
                'amount', 'occurred_at', 'type', 'notes', 'line', 'file', 'beneficiary_id', 'raw_name', 'pretty_name'
            ])
            ->join('beneficiaries as b', 'b.id', 'transactions.beneficiary_id')
            ->where('transactions.id', $id)
            ->firstOrFail();
    }
}
