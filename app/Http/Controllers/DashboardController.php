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
        $data = $this->getIndexData();

        return view('dashboard.index', $data);
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

    /**
     * @return array
     */
    private function getIndexData(): array
    {
        $data = [
            'transac_expanses' => Transaction::getList(
                ['sign' => 'negative', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')],
                self::EXP_PER_PAGE
            ),
            'transac_revenus' => Transaction::getList(
                ['sign' => 'positive', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')]
            ),
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
            'first_date' => Transaction::query()
                ->select(DB::raw('DATE(occurred_at) as occurred_at'))
                ->orderBy('occurred_at')
                ->first()
        ];

        $expanses = Transaction::getList(
            ['sign' => 'negative', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')], false
        );

        $revenus = Transaction::getList(
            ['sign' => 'positive', 'date_start' => Carbon::now()->startOfMonth()->format('Y-m-d')], false
        );

        $data['values_exp_vs_rev'] = [
            'labels' => ['Dépenses', 'Revenus'],
            'values' => [
                abs($expanses->pluck('amount')->sum()),
                $revenus->pluck('amount')->sum()
            ],
        ];

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
