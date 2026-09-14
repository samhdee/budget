<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Label;
use App\Models\TransacRecurringPattern;
use App\Models\Transaction;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected const int EXP_PER_PAGE = 25;

    public function index()
    {
        $date_start = !empty($filters['date_start'])
            ? $filters['date_start'] . '-01'
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $date_end = !empty($filters['date_start'])
            ? Carbon::parse($date_start)->endOfMonth()->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        return view('dashboard.index', [
            'expanses' => Transaction::getList(
                [
                    'sign' => 'negative',
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                ],
                false
            ),
            'revenus' => Transaction::getList(
                [
                    'sign' => 'positive',
                    'date_start' => $date_start,
                    'date_end' => $date_end,
                ],
                false
            ),
            'active_recurrences' => TransacRecurringPattern::getList(),
            'filter_date_start' => $date_start,
            'filter_date_end' => $date_end,
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
            'labels' => Label::getList(),
            'first_date' => Transaction::getFirstDate(),
        ]);
    }
}
