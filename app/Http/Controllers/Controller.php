<?php

namespace App\Http\Controllers;

use App\Enums\SortDirection;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected function getSortDir(Request $request, SortDirection $default)
    {
        if (empty($request->input('sort'))) {
            return $default;
        }

        return $request->input('sort')['direction'] === SortDirection::asc->value
            ? SortDirection::desc->value
            : SortDirection::asc->value;
    }
}
