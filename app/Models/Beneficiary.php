<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
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
 * @property string|null $notes
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Transaction> $transactions
 * @property-read int|null $transactions_count
 * @method static Builder<static>|Beneficiary newModelQuery()
 * @method static Builder<static>|Beneficiary newQuery()
 * @method static Builder<static>|Beneficiary query()
 * @method static Builder<static>|Beneficiary whereCategoryId($value)
 * @method static Builder<static>|Beneficiary whereCreatedAt($value)
 * @method static Builder<static>|Beneficiary whereDescription($value)
 * @method static Builder<static>|Beneficiary whereExpression($value)
 * @method static Builder<static>|Beneficiary whereId($value)
 * @method static Builder<static>|Beneficiary whereNotes($value)
 * @method static Builder<static>|Beneficiary wherePrettyName($value)
 * @method static Builder<static>|Beneficiary whereRawName($value)
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
        'description',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'beneficiary_id');
    }

    public static function getList($filters = []): LengthAwarePaginator
    {
        $query = self::query()
            ->select([
                'beneficiaries.id', 'raw_name', 'pretty_name', 'beneficiaries.description', 'category_id',
                'c.appellation as c_appellation', 'c.color as c_color',
            ])
            ->withCount('transactions as nb_transactions')
            ->leftJoin('categories as c', 'c.id', 'category_id');

        if (!empty($filters['either_name'])) {
            $query->where(function (Builder $query) use ($filters) {
                $query->orWhereLike('raw_name', "%{$filters['either_name']}%")
                    ->orWhereLike('pretty_name', "%{$filters['either_name']}%");
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
            ->select(['id', 'raw_name', 'pretty_name', 'category_id', 'notes'])
            ->where('id', $benef_id)
            ->firstOrFail();
    }
}
