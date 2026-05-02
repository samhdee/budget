<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $raw_name
 * @property string|null $expression Formule permettant de reconnaître le bénéficiaire dans les futures extractions
 * @property string|null $Description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $pretty_name
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereExpression($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary wherePrettyName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereRawName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Beneficiary whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Beneficiary extends Model
{
    protected $table = 'beneficiaries';
    protected $fillable = [
        'raw_name',
        'pretty_name',
    ];

    /**
     * @return Collection
     */
    public static function getList(): Collection
    {
        return self::query()
            ->select(['id', 'raw_name', 'pretty_name'])
            ->orderBy('pretty_name')
            ->orderBy('raw_name')
            ->get();
    }
}
