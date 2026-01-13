<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperLabelModel
 */
class LabelModel extends Model
{
    protected $table = 'labels';
    protected $fillable = [
        'name',
        'description',
    ];
}
