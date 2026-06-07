<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $label
 * @property string $expected_frequency
 * @property int $beneficiary_id
 * @property int $active
 * @property string $frequency_unit
 * @property int|null $frequency_count
 * @property string $type
 * @property float $amount
 * @property Carbon|null $ends_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon|null $deleted_at
 * @property-read Beneficiary|null $beneficiary
 * @method static Builder<static>|TransacRecurringPattern newModelQuery()
 * @method static Builder<static>|TransacRecurringPattern newQuery()
 * @method static Builder<static>|TransacRecurringPattern onlyTrashed()
 * @method static Builder<static>|TransacRecurringPattern query()
 * @method static Builder<static>|TransacRecurringPattern whereActive($value)
 * @method static Builder<static>|TransacRecurringPattern whereBeneficiaryId($value)
 * @method static Builder<static>|TransacRecurringPattern whereCreatedAt($value)
 * @method static Builder<static>|TransacRecurringPattern whereDeletedAt($value)
 * @method static Builder<static>|TransacRecurringPattern whereExpectedFrequency($value)
 * @method static Builder<static>|TransacRecurringPattern whereFrequencyCount($value)
 * @method static Builder<static>|TransacRecurringPattern whereFrequencyUnit($value)
 * @method static Builder<static>|TransacRecurringPattern whereType($value)
 * @method static Builder<static>|TransacRecurringPattern whereAmount($value)
 * @method static Builder<static>|TransacRecurringPattern whereId($value)
 * @method static Builder<static>|TransacRecurringPattern whereLabel($value)
 * @method static Builder<static>|TransacRecurringPattern whereEndsAt($value)
 * @method static Builder<static>|TransacRecurringPattern whereUpdatedAt($value)
 * @method static Builder<static>|TransacRecurringPattern withTrashed(bool $withTrashed = true)
 * @method static Builder<static>|TransacRecurringPattern withoutTrashed()
 * @mixin Eloquent
 */
class TransacRecurringPattern extends Model
{
    protected $table = 'transac_recurring_patterns';

    protected $fillable = [
        'label',
        'expected_frequency',
        'beneficiary_id',
        'active',
        'frequency_unit',
        'frequency_count',
        'type',
        'amount',
        'ends_at',
    ];

    public function beneficiary(): BelongsTo
    {
        return $this->belongsTo(Beneficiary::class);
    }

    public function transactions(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'id', 'recurring_pattern_id');
    }

    /**
     * @return string
     */
    public function getUnitLabel(): string
    {
        return match ($this->frequency_unit) {
            'week' => 'semaine' . ($this->frequency_count > 1 ? 's' : ''),
            default => 'mois',
        };
    }

    /**
     * @param array $filters
     * @return Collection
     */
    public static function getList(array $filters = []): Collection
    {
        $query = self::query()
            ->select([
                'transac_recurring_patterns.id', 'label', 'beneficiary_id', 'amount', 'frequency_unit',
                'frequency_count', 'ends_at', 'raw_name', 'pretty_name',
            ])
            ->join('beneficiaries as b', 'b.id', 'beneficiary_id')
            ->whereHas('transactions')
            ->withCount('transactions as nb_transactions');


        if (isset($filters['active'])) {
            $query->where('active', $filters['active']);
        } else {
            $query->where('active', 1);
        }

        if (!empty($filters['past'])) {
            $query->whereDate('ends_at', '<', Carbon::now());
        } else {
            $query->where(function (Builder $query) {
                $query->orWhereDate('ends_at', '>=', Carbon::now())
                    ->orWhereNull('ends_at');
            });
        }

        return $query->orderBy('label')
            ->orderBy('b.raw_name')
            ->orderBy('b.pretty_name')
            ->get();
    }

    public static function getOne($recurrence_id): ?TransacRecurringPattern
    {
        return self::query()
            ->select(['id', 'label', 'beneficiary_id', 'amount', 'frequency_unit', 'frequency_count', 'ends_at'])
            ->whereHas('transactions')
            ->where('id', $recurrence_id)
            ->first();
    }

    public static function getActiveMonthlyRecurrences(): Collection
    {
        return self::query()
            ->select(['id', 'amount', 'frequency_count', 'frequency_unit'])
            ->where('active', 1)
            ->where(function (Builder $query) {
                $query->orWhere('frequency_unit', 'week')
                    ->orWhere('frequency_count', 1);
            })
            ->where(function (Builder $query) {
                $query->orWhereNull('ends_at')
                    ->orWhereDate('ends_at', '>=', Carbon::now());
            })
            ->get();
    }
}
