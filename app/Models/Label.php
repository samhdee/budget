<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $appellation
 * @property string|null $color
 * @property string|null $description
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string|null $deleted_at
 * @method static Builder<static>|Label newModelQuery()
 * @method static Builder<static>|Label newQuery()
 * @method static Builder<static>|Label query()
 * @method static Builder<static>|Label whereCreatedAt($value)
 * @method static Builder<static>|Label whereDeletedAt($value)
 * @method static Builder<static>|Label whereDescription($value)
 * @method static Builder<static>|Label whereId($value)
 * @method static Builder<static>|Label whereAppellation($value)
 * @method static Builder<static>|Label whereColor($value)
 * @method static Builder<static>|Label whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Label extends Model
{
    protected $table = 'labels';
    protected $fillable = [
        'appellation',
        'color',
        'description',
    ];
}
