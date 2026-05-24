<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    protected const int EXP_PER_PAGE = 25;

    public function index()
    {
        return view('dashboard.index', [
            'transac_expanses' => Transaction::getList(
                ['sign' => 'negative', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')],
                self::EXP_PER_PAGE
            ),
            'transac_revenus' => Transaction::getList(
                ['sign' => 'positive', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')]
            ),
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
        ]);
    }

    public function expFilter(Request $request)
    {
        $filters = array_merge(
            $request->input('filters'),
            ['sign' => 'negative', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')],
        );

        return view('dashboard.expanses-list', [
            'transac_expanses' => Transaction::getList($filters, self::EXP_PER_PAGE),
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
        ]);
    }
}
