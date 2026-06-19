<?php

namespace App\Models;

use App\Enums\TransactionType;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * @property int $id
 * @property string $occurred_at
 * @property numeric $amount
 * @property int|null $beneficiary_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int|null $category_id
 * @property string $type
 * @property int|null $line
 * @property string|null $file
 * @property string|null $notes
 * @property int|null $recurring_pattern_id
 * @property-read Beneficiary|null $beneficiary
 * @property-read Category|null $category
 * @property-read TransacRecurringPattern|null $recurringPattern
 * @method static Builder<static>|Transaction newModelQuery()
 * @method static Builder<static>|Transaction newQuery()
 * @method static Builder<static>|Transaction query()
 * @method static Builder<static>|Transaction whereAmount($value)
 * @method static Builder<static>|Transaction whereBeneficiaryId($value)
 * @method static Builder<static>|Transaction whereCategoryId($value)
 * @method static Builder<static>|Transaction whereRecurringPatternId($value)
 * @method static Builder<static>|Transaction whereCreatedAt($value)
 * @method static Builder<static>|Transaction whereFile($value)
 * @method static Builder<static>|Transaction whereId($value)
 * @method static Builder<static>|Transaction whereLine($value)
 * @method static Builder<static>|Transaction whereNotes($value)
 * @method static Builder<static>|Transaction whereOccurredAt($value)
 * @method static Builder<static>|Transaction whereType($value)
 * @method static Builder<static>|Transaction whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Transaction extends Model
{
    protected $table = 'transactions';
    protected $perPage = 50;
    protected string $paginationTheme = 'bootstrap';
    protected $fillable = [
        'occurred_at',
        'amount',
        'notes',
        'beneficiary_id',
        'category_id',
        'type',
        'line',
        'file',
        'recurring_pattern_id',
    ];

    public function beneficiary(): HasOne
    {
        return $this->hasOne(Beneficiary::class, 'id', 'beneficiary_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function labels(): BelongsToMany
    {
        return $this->belongsToMany(
            Label::class,
            'labels_transactions',
            'transaction_id',
            'label_id'
        );
    }

    public function recurringPattern(): HasOne
    {
        return $this->hasOne(TransacRecurringPattern::class, 'id', 'recurring_pattern_id');
    }

    /**
     * @param array $filters
     * @param int|bool $per_page
     * @return Collection|LengthAwarePaginator
     */
    public static function getList(array $filters = [], int|bool $per_page = 50): Collection|LengthAwarePaginator
    {
        $query = self::query()
            ->select([
                'transactions.id', 'amount', 'occurred_at', 'type', 'transactions.notes', 'line', 'file',
                'beneficiary_id', 'recurring_pattern_id', 'category_id',
            ])
            ->with('recurringPattern:id,active')
            ->with('beneficiary:id,raw_name,pretty_name,description')
            ->with('category:id,appellation,goal')
            ->with('labels:id,appellation,goal');

        if (!empty($filters['sign'])) {
            if ($filters['sign'] === 'negative') {
                $query->where('amount', '<', 0);
            } else {
                $query->where('amount', '>', 0);
            }
        }

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('category', function (Builder $query) use ($filters) {
                $query->where('categories.id', $filters['category_id']);
            });
        }

        if (!empty($filters['label_id'])) {
            $query->whereHas('labels', function (Builder $query) use ($filters) {
                $query->where('labels.id', $filters['label_id']);
            });
        }

        if (!empty($filters['benef_name'])) {
            $query->whereHas('beneficiary', function (Builder $query) use ($filters) {
                $query->where(function (Builder $query) use ($filters) {
                    $query->orWhereLike('raw_name', "%{$filters['benef_name']}%")
                        ->orWhereLike('pretty_name', "%{$filters['benef_name']}%");
                });
            });
        }

        if (!empty($filters['date_start'])) {
            $query->whereDate('occurred_at', '>=', $filters['date_start']);
        }

        if (!empty($filters['date_end'])) {
            $query->whereDate('occurred_at', '<=', $filters['date_end']);
        }

        $query->orderByDesc('occurred_at')
            ->orderByDesc('created_at');

        if (!empty($per_page)) {
            return $query->paginate($per_page);
        }

        return $query->get();
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
            ->with([
                'labels:id,appellation',
                'recurringPattern:id,active',
            ])
            ->join('beneficiaries as b', 'b.id', 't.beneficiary_id')
            ->where('t.id', $id)
            ->firstOrFail();
    }

    /**
     * @param $recurrence_id
     * @return Collection
     */
    public static function getFromRecurrence($recurrence_id): Collection
    {
        return self::query()
            ->select(['id', 'amount', DB::raw('DATE_FORMAT(occurred_at, "%d/%m/%Y") as occurred_at'), 'category_id', 'beneficiary_id'])
            ->with(['category:id,appellation', 'beneficiary:id,raw_name,pretty_name'])
            ->where('recurring_pattern_id', $recurrence_id)
            ->orderByDesc('transactions.occurred_at')
            ->get();
    }

    /**
     * @param Transaction $transaction
     * @param bool $with_recurrence
     * @return Collection
     */
    public static function getSimilar(Transaction $transaction, bool $with_recurrence = false): Collection
    {
        $query = Transaction::query()
            ->select(['transactions.id', 'amount', 'occurred_at', 'beneficiary_id', 'recurring_pattern_id'])
            ->whereDate('occurred_at', '<', $transaction->occurred_at);

        if ($with_recurrence) {
            $query->whereHas('recurringPattern');
        } else {
            $query->whereDoesntHave('recurringPattern');
        }

        if ($transaction->amount > 30) {
            $query->where('amount', '<=', $transaction->amount - 1.5)
                ->where('amount', '>=', $transaction->amount + 1.5);
        } else {
            $query->where('amount', '<=', 0.95 * $transaction->amount)
                ->where('amount', '>=', 1.05 * $transaction->amount);
        }

        return $query->where('beneficiary_id', $transaction->beneficiary_id)
            ->orderByDesc('occurred_at')
            ->get();
    }
}
