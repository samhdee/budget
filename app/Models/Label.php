<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $appellation
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @property string|null $color
 * @property float|null $goal
 * @method static Builder<static>|Label newModelQuery()
 * @method static Builder<static>|Label newQuery()
 * @method static Builder<static>|Label query()
 * @method static Builder<static>|Label whereAppellation($value)
 * @method static Builder<static>|Label whereColor($value)
 * @method static Builder<static>|Label whereGoal($value)
 * @method static Builder<static>|Label whereCreatedAt($value)
 * @method static Builder<static>|Label whereDeletedAt($value)
 * @method static Builder<static>|Label whereDescription($value)
 * @method static Builder<static>|Label whereId($value)
 * @method static Builder<static>|Label whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Label extends Model
{
    protected $table = 'labels';
    protected $fillable = [
        'appellation',
        'color',
        'goal',
        'description',
    ];

    public function transactions(): BelongsToMany
    {
        return $this->belongsToMany(
            Transaction::class,
            'labels_transactions',
            'label_id',
            'transaction_id'
        );
    }

    public function monthExpanses(): BelongsToMany
    {
        return $this->belongsToMany(
            Transaction::class,
            'labels_transactions',
            'label_id',
            'transaction_id'
        )
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
            ->select(['id', 'appellation'])
            ->orderBy('appellation')
            ->get();
    }

    /**
     * @param $label_id
     * @return Label
     * @throws ModelNotFoundException
     */
    public static function getOne($label_id): Label
    {
        return self::query()
            ->select(['id', 'appellation', 'goal', 'description'])
            ->where('id', $label_id)
            ->orderBy('appellation')
            ->firstOrFail();
    }
}
