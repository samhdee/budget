<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $raw_name
 * @property string|null $expression Formule permettant de reconnaître le bénéficiaire dans les futures extractions
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $pretty_name
 * @property int|null $category_id
 * @property int|null $label_id
 * @property string|null $notes
 * @property boolean|null $non_recurring
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder<static>|Beneficiary newModelQuery()
 * @method static Builder<static>|Beneficiary newQuery()
 * @method static Builder<static>|Beneficiary query()
 * @method static Builder<static>|Beneficiary whereCategoryId($value)
 * @method static Builder<static>|Beneficiary whereLabelId($value)
 * @method static Builder<static>|Beneficiary whereCreatedAt($value)
 * @method static Builder<static>|Beneficiary whereDescription($value)
 * @method static Builder<static>|Beneficiary whereExpression($value)
 * @method static Builder<static>|Beneficiary whereId($value)
 * @method static Builder<static>|Beneficiary whereNotes($value)
 * @method static Builder<static>|Beneficiary wherePrettyName($value)
 * @method static Builder<static>|Beneficiary whereRawName($value)
 * @method static Builder<static>|Beneficiary whereNonRecurring($value)
 * @method static Builder<static>|Beneficiary whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Beneficiary extends Model
{
    protected $table = 'beneficiaries';
    protected $perPage = 50;
    protected string $paginationTheme = 'bootstrap';
    protected $fillable = [
        'raw_name',
        'pretty_name',
        'category_id',
        'label_id',
        'description',
        'non_recurring',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'beneficiary_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(Category::class, 'id', 'category_id');
    }

    public function label(): HasOne
    {
        return $this->hasOne(Label::class, 'id', 'label_id');
    }

    public static function getList($filters = []): LengthAwarePaginator
    {
        $query = self::query()
            ->select(['id', 'raw_name', 'pretty_name', 'non_recurring', 'description', 'category_id', 'label_id'])
            ->with([
                'category:id,appellation',
                'label:id,appellation',
            ])
            ->withCount('transactions as nb_transactions');

        if (!empty($filters['either_name'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->orWhereLike('raw_name', "%{$filters['either_name']}%")
                    ->orWhereLike('pretty_name', "%{$filters['either_name']}%");
            });
        }

        if (!empty($filters['with_transac'])) {
            if ($filters['with_transac'] === 'true') {
                $query->whereHas('transactions');
            } elseif ($filters['with_transac'] === 'false') {
                $query->whereDoesntHave('transactions');
            }
        }

        if (!empty($filters['category_id'])) {
            $query->whereHas('category', function (Builder $query) use ($filters) {
                $query->where('id', $filters['category_id']);
            });
        }

        if (!empty($filters['label_id'])) {
            $query->whereHas('label', function (Builder $query) use ($filters) {
                $query->where('id', $filters['label_id']);
            });
        }

        return $query->orderBy('raw_name')
            ->orderBy('pretty_name')
            ->paginate();
    }

    /**
     * @return Collection
     */
    public static function getDropdownList(): Collection
    {
        return self::query()
            ->select(['id', 'raw_name', 'pretty_name'])
            ->orderBy('raw_name')
            ->get();
    }

    /**
     * @param $benef_id
     * @return Beneficiary
     * @throws ModelNotFoundException
     */
    public static function getOne($benef_id): Beneficiary
    {
        return self::query()
            ->select(['id', 'raw_name', 'pretty_name', 'category_id', 'label_id', 'description', 'non_recurring'])
            ->where('id', $benef_id)
            ->firstOrFail();
    }
}
