<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelModel extends Model
{
    protected $table = 'labels';
    protected $fillable = [
        'name',
        'description',
    ];
}
