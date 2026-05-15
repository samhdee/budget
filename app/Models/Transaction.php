<?php

namespace App\Models;

use App\Enums\TransactionType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;

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
    protected $perPage = 50;
    protected string $paginationTheme = 'bootstrap';
    protected $table = 'transactions';
    protected $fillable = [
        'occurred_at',
        'amount',
        'notes',
        'beneficiary_id',
        'category_id',
        'type',
        'line',
        'file',
    ];

    public function beneficiary(): HasOne
    {
        return $this->hasOne(Beneficiary::class, 'beneficiary_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'category_id');
    }

    /**
     * @param array $filters
     * @return LengthAwarePaginator
     */
    public static function getList(array $filters = []): LengthAwarePaginator
    {
        $query = self::query()
            ->select([
                't.id', 'amount', 'occurred_at', 'type', 't.notes', 'line', 'file', 'beneficiary_id', 'b.raw_name',
                'b.pretty_name', 'b.notes as benef_notes', 'c.appellation as c_appellation', 'c.color as c_color',
            ])
            ->from('transactions as t')
            ->join('beneficiaries as b', 'b.id', 't.beneficiary_id')
            ->leftJoin('categories as c', 'c.id', 't.category_id');

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
            ->paginate(50);
    }

    /**
     * @param int $id
     * @return Transaction
     * @throws ModelNotFoundException
     */
    public static function getOne(int $id): Transaction
    {
        return self::query()
            ->select([
                't.id', 'amount', 'occurred_at', 'type', 't.notes', 'line', 'file', 'beneficiary_id', 't.category_id',
                'b.raw_name', 'b.pretty_name',
            ])
            ->from('transactions as t')
            ->join('beneficiaries as b', 'b.id', 't.beneficiary_id')
            ->where('t.id', $id)
            ->firstOrFail();
    }
}
