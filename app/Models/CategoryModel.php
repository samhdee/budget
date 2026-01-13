<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @mixin IdeHelperCategoryModel
 */
class CategoryModel extends Model
{
    protected $table = 'categories';
    protected $fillable = [
        'name',
        'color',
        'description',
    ];
}
