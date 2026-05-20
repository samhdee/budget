<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\TransacRecurringPattern;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RecurrencesController extends Controller
{
    public function index()
    {
        return view('recurrences.index', [
            'recurrences' => TransacRecurringPattern::getList(),
            'beneficiaries' => Beneficiary::getDropdownList(),
        ]);
    }

    /**
     * @return View
     */
    public function filter()
    {
        return view('recurrences.list', [
            'recurrences' => TransacRecurringPattern::getList(),
        ]);
    }

    public function get($recurrence_id)
    {
        return response()->json(['item' => TransacRecurringPattern::getOne($recurrence_id)]);
    }

    /**
     * @return RedirectResponse
     */
    // @FIXME: Trop long
    public function detectRecurrences()
    {
        $now = Carbon::now();
        $transactions = Transaction::query()
            ->select(['transactions.id', 'amount', 'occurred_at', 'beneficiary_id', 'raw_name', 'pretty_name'])
            ->join('beneficiaries as b', 'b.id', 'transactions.beneficiary_id')
            ->whereDoesntHave('recurringPattern')
            ->whereDate('occurred_at', '>=', $now->startOfMonth()->format('Y-m-d'))
            ->whereDate('occurred_at', '<=', $now->endOfMonth()->format('Y-m-d'))
            ->where('amount', '<', 0)
            ->orderByDesc('occurred_at')
            ->get();

        $two_months_ago = $now->subMonths(2)->startOfMonth()->format('Y-m-d');

        foreach ($transactions as $transaction) {
            $query = Transaction::query()
                ->select(['transactions.id', 'amount', 'occurred_at', 'beneficiary_id'])
                ->whereDoesntHave('recurringPattern')
                ->whereDate('occurred_at', '<', $transaction->occurred_at)
                ->whereDate('occurred_at', '>=', $two_months_ago);

            // @FIXME: utiliser min/max à la place d'un if
            if ($transaction->amount > 30) {
                $query->where('amount', '<=', $transaction->amount - 1.5)
                    ->where('amount', '>=', $transaction->amount + 1.5);
            } else {
                $query->where('amount', '<=', 0.95 * $transaction->amount)
                    ->where('amount', '>=', 1.05 * $transaction->amount);
            }

            $previous_transacs = $query->where('beneficiary_id', $transaction->beneficiary_id)
                ->orderByDesc('occurred_at')
                ->get();

            if ($previous_transacs->isEmpty()) {
                continue;
            }

            $new_recurrence = new TransacRecurringPattern();
            $new_recurrence->beneficiary_id = $transaction->beneficiary_id;
            $new_recurrence->amount = $transaction->amount;
            $new_recurrence->active = 1;
            $new_recurrence->label = !empty($transaction->pretty_name) ? $transaction->pretty_name : $transaction->raw_name;

            $prev_trans_date = Carbon::parse($previous_transacs->first()->occurred_at);
            $diff_in_days = $prev_trans_date->diffInDays($transaction->occurred_at);

            if ($diff_in_days >= 57) {
                $new_recurrence->frequency_count = 2;
                $new_recurrence->frequency_unit = 'month';
            } elseif ($diff_in_days >= 27) {
                $new_recurrence->frequency_count = 1;
                $new_recurrence->frequency_unit = 'month';
            } elseif ($diff_in_days >= 12) {
                $new_recurrence->frequency_count = 2;
                $new_recurrence->frequency_unit = 'week';
            } else {
                $new_recurrence->frequency_count = 1;
                $new_recurrence->frequency_unit = 'week';
            }

            $new_recurrence->save();
            $transac_ids = $previous_transacs->pluck('id')->toArray();
            $transac_ids[] = $transaction->id;

            Transaction::whereIn('id', $transac_ids)
                ->update(['recurring_pattern_id' => $new_recurrence->id]);
        }

        return to_route('import_index');
    }
}
