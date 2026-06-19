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
        return view('dashboard.index', $this->getIndexData());
    }

    public function filter(Request $request)
    {
        return view('dashboard.lists', $this->getIndexData($request->input('filters')));
    }

    public function expFilter(Request $request)
    {
        $filters = array_merge(
            ['sign' => 'negative', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')],
            $request->input('filters'),
        );

        return view('dashboard.expanses-list', [
            'transac_expanses' => Transaction::getList($filters, self::EXP_PER_PAGE),
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
        ]);
    }

    /**
     * @param array $filters
     * @return array
     */
    private function getIndexData(array $filters = []): array
    {
        $date_start = !empty($filters['date_start'])
            ? $filters['date_start'] . '-01'
            : Carbon::now()->startOfMonth()->format('Y-m-d');

        $date_end = !empty($filters['date_start'])
            ? Carbon::parse($date_start)->endOfMonth()->format('Y-m-d')
            : Carbon::now()->endOfMonth()->format('Y-m-d');

        return [
            'transac_expanses' => Transaction::getList(
                [
                    'sign' => 'negative',
                    'date_start' => $date_start,
                    'date_end' => $date_end
                ],
                self::EXP_PER_PAGE
            ),
            'transac_revenus' => Transaction::getList([
                'sign' => 'positive',
                'date_start' => $date_start,
                'date_end' => $date_end
            ]),
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
            'first_date' => Transaction::query()
                ->select(DB::raw('DATE(occurred_at) as occurred_at'))
                ->orderBy('occurred_at')
                ->first(),
            'active_tab' => $filters['active_tab'] ?? 'general-tab',
        ];
    }
}
