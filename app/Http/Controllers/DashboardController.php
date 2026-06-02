<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
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

        $data = [
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
            'filter_date_start' => $date_start,
            'filter_date_end' => $date_end,
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
            'first_date' => Transaction::query()
                ->select(DB::raw('DATE(occurred_at) as occurred_at'))
                ->orderBy('occurred_at')
                ->first()
        ];

        $expanses = Transaction::getList(
            ['sign' => 'negative', 'date_start' => $date_start, 'date_end' => $date_end], false
        );

        $revenus = Transaction::getList(
            ['sign' => 'positive', 'date_start' => $date_start, 'date_end' => $date_end], false
        );

        if ($expanses->isNotEmpty() || $revenus->isNotEmpty()) {
            $data['values_exp_vs_rev'] = [
                'labels' => ['Dépenses', 'Revenus'],
                'values' => [
                    abs($expanses->pluck('amount')->sum()),
                    $revenus->pluck('amount')->sum()
                ],
            ];
        }

        $expanses_with_categ = $expanses->filter(function ($item) {
            return !empty($item->category_id) && !empty($item->c_appellation);
        })
            ->values()
            ->groupBy('category_id');

        foreach ($expanses_with_categ as $expanse) {
            $data['values_exp_by_categ']['labels'][] = $expanse->first()->c_appellation;
            $data['values_exp_by_categ']['values'][] = abs($expanse->pluck('amount')->sum());
        }

        return $data;
    }
}
