<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $appellation
 * @property string|null $color
 * @property float|null $goal
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder<static>|Category newModelQuery()
 * @method static Builder<static>|Category newQuery()
 * @method static Builder<static>|Category query()
 * @method static Builder<static>|Category whereAppellation($value)
 * @method static Builder<static>|Category whereColor($value)
 * @method static Builder<static>|Category whereGoal($value)
 * @method static Builder<static>|Category whereCreatedAt($value)
 * @method static Builder<static>|Category whereDeletedAt($value)
 * @method static Builder<static>|Category whereDescription($value)
 * @method static Builder<static>|Category whereId($value)
 * @method static Builder<static>|Category whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'appellation',
        'color',
        'goal',
        'description',
    ];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'category_id');
    }

    public function monthExpanses(): HasMany
    {
        return $this->hasMany(Transaction::class, 'category_id')
            ->where('occurred_at', '>=', Carbon::now()->startOfMonth())
            ->where('amount', '<', 0);
    }

    public static function getList(): Collection
    {
        return self::query()
            ->select(['id', 'appellation', 'goal', 'description'])
            ->withCount('transactions as nb_transactions')
            ->orderBy('appellation')
            ->get();
    }

    /**
     * @return Collection
     */
    public static function getDropdownList(): Collection
    {
        return self::query()
            ->select(['id', 'appellation', 'goal'])
            ->orderBy('appellation')
            ->get();
    }

    /**
     * @param $categ_id
     * @return Category
     * @throws ModelNotFoundException
     */
    public static function getOne($categ_id): Category
    {
        return self::query()
            ->select(['id', 'appellation', 'goal', 'description'])
            ->where('id', $categ_id)
            ->orderBy('appellation')
            ->firstOrFail();
    }
}
