<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $raw_name
 * @property string $pretty_name
 * @property string|null $notes
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder<static>|Beneficiary newModelQuery()
 * @method static Builder<static>|Beneficiary newQuery()
 * @method static Builder<static>|Beneficiary query()
 * @method static Builder<static>|Beneficiary whereId($value)
 * @method static Builder<static>|Beneficiary whereRawName($value)
 * @method static Builder<static>|Beneficiary wherePrettyName($value)
 * @method static Builder<static>|Beneficiary whereNotes($value)
 * @method static Builder<static>|Beneficiary whereCreatedAt($value)
 * @method static Builder<static>|Beneficiary whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Beneficiary extends Model
{
    protected $table = 'beneficiaries';
    protected $fillable = [
        'raw_name',
        'pretty_name',
        'notes',
    ];

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
            ->select(['raw_name', 'pretty_name', 'notes'])
            ->where('id', $benef_id)
            ->firstOrFail();
    }
}
