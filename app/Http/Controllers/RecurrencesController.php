<?php

namespace App\Http\Controllers;

use App\Enums\RecurrenceFreqUnit;
use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Label;
use App\Models\TransacRecurringPattern;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Throwable;
use function validator;

class RecurrencesController extends Controller
{
    public function index()
    {
        return view('recurrences.index', [
            'recurrences' => TransacRecurringPattern::getList(),
            'past_recurrences' => TransacRecurringPattern::getList(['past' => true]),
            'inactive_recurrences' => TransacRecurringPattern::getList(['active' => 0]),
            'beneficiaries' => Beneficiary::getDropdownList(true),
            'categories' => Category::getDropdownList(),
            'labels' => Label::getDropdownList(),
        ]);
    }

    /**
     * @return View
     */
    public function filter()
    {
        return view('recurrences.lists', [
            'recurrences' => TransacRecurringPattern::getList(),
            'past_recurrences' => TransacRecurringPattern::getList(['past' => true]),
            'inactive_recurrences' => TransacRecurringPattern::getList(['active' => 0]),
        ]);
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
            ->where(function (Builder $query) {
                $query->orWhere('non_recurring', '!=', 1)
                    ->orWhereNull('non_recurring');
            })
            ->orderByDesc('occurred_at')
            ->get();

        $nb_found = 0;

        foreach ($transactions as $transaction) {
            $previous_transacs = Transaction::getSimilar($transaction);

            if ($previous_transacs->isEmpty()) {
                continue;
            }

            $nb_found++;

            $new_recurrence = new TransacRecurringPattern();
            $new_recurrence->beneficiary_id = $transaction->beneficiary_id;
            $new_recurrence->amount = $transaction->amount;
            $new_recurrence->active = 1;
            $new_recurrence->label = !empty($transaction->pretty_name) ? $transaction->pretty_name : $transaction->raw_name;

            $prev_trans_date = Carbon::parse($previous_transacs->first()->occurred_at);
            $diff_in_days = $prev_trans_date->diffInDays($transaction->occurred_at);

            if ($diff_in_days >= 360) {
                $new_recurrence->frequency_count = 12;
                $new_recurrence->frequency_unit = 'month';
            } elseif ($diff_in_days >= 117) {
                $new_recurrence->frequency_count = 4;
                $new_recurrence->frequency_unit = 'month';
            } elseif ($diff_in_days >= 87) {
                $new_recurrence->frequency_count = 3;
                $new_recurrence->frequency_unit = 'month';
            } elseif ($diff_in_days >= 57) {
                $new_recurrence->frequency_count = 2;
                $new_recurrence->frequency_unit = 'month';
            } elseif ($diff_in_days >= 26) {
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

        return to_route('recurrences_index')
            ->with('message', "{$nb_found} récurrence(s) trouvée(s) !");
    }

    public function get($recurrence_id)
    {
        validator(\request()->route()->parameters(), [
            'id' => ['required', 'integer', 'exists:' . TransacRecurringPattern::class],
        ])->validate();

        return response()->json(['item' => TransacRecurringPattern::getOne($recurrence_id)]);
    }

    public function getTransacs($recurrence_id)
    {
        validator(\request()->route()->parameters(), [
            'id' => ['required', 'integer', 'exists:' . TransacRecurringPattern::class],
        ])->validate();

        return response()->json(['item' => Transaction::getFromRecurrence($recurrence_id)]);
    }

    public function searchTransacs($recurrence_id)
    {
        validator(\request()->route()->parameters(), [
            'id' => ['required', 'integer', 'exists:' . TransacRecurringPattern::class],
        ])->validate();

        $recurrence = TransacRecurringPattern::query()
            ->select(['beneficiary_id'])
            ->with('beneficiary:id,raw_name')
            ->where('id', $recurrence_id)
            ->firstOrFail();

        return Transaction::query()
            ->select(['id', 'amount', 'category_id', 'beneficiary_id'])
            ->with([
                'beneficiary:id,raw_name,pretty_name',
                'category:id,appellation'
            ])
            ->where('beneficiary_id', $recurrence->beneficiary_id)
            ->get();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . TransacRecurringPattern::class],
            'label' => ['nullable', 'max:255'],
            'amount' => ['required', 'decimal:2'],
            'beneficiary_id' => ['required', 'exists:' . Beneficiary::class . ',id'],
            'frequency_count' => ['required', 'integer'],
            'frequency_unit' => ['required', Rule::in(RecurrenceFreqUnit::cases())],
            'ends_at' => ['nullable', 'date'],
        ]);

        if (empty($data['id'])) {
            $recurrence = new TransacRecurringPattern();
            $recurrence->label = $data['label'];
            $recurrence->amount = $data['amount'];
            $recurrence->beneficiary_id = $data['beneficiary_id'];
            $recurrence->frequency_count = $data['frequency_count'];
            $recurrence->frequency_unit = $data['frequency_unit'];
            $recurrence->ends_at = $data['ends_at'];
            $nb_updated = $recurrence->save();
        } else {
            $nb_updated = TransacRecurringPattern::where('id', $data['id'])
                ->update([
                    'label' => $data['label'],
                    'amount' => $data['amount'],
                    'beneficiary_id' => $data['beneficiary_id'],
                    'frequency_count' => $data['frequency_count'],
                    'frequency_unit' => $data['frequency_unit'],
                    'ends_at' => $data['ends_at'],
                ]);
        }

        return response()->json([
            'updated' => $nb_updated,
            'view' => view('recurrences.lists', [
                'recurrences' => TransacRecurringPattern::getList(),
                'past_recurrences' => TransacRecurringPattern::getList(['past' => true]),
                'inactive_recurrences' => TransacRecurringPattern::getList(['active' => 0]),
            ])->render(),
        ]);
    }

    public function syncTransacs($recurrence_id)
    {
        validator(\request()->route()->parameters(), [
            'id' => ['required', 'integer', 'exists:' . TransacRecurringPattern::class],
        ])->validate();

        $recurrence = TransacRecurringPattern::find($recurrence_id);
    }

    /**
     * @param Request $request
     * @param int|null $recurrence_id
     * @return JsonResponse
     * @throws Throwable
     */
    public function toggleActive(Request $request, ?int $recurrence_id = null)
    {
        if (!empty($recurrence_id)) {
            validator(\request()->route()->parameters(), [
                'recurrence_id' => ['required', 'integer', 'exists:' . TransacRecurringPattern::class . ',id'],
            ])->validate();
            $data['item_ids'] = [$recurrence_id];
        } else {
            $data = $request->validate([
                'item_ids.*' => Rule::forEach(function () {
                    return [
                        Rule::exists(TransacRecurringPattern::class, 'id'),
                    ];
                }),
            ]);
        }

        $recurrences = TransacRecurringPattern::query()
            ->select(['id', 'active', 'ends_at'])
            ->whereIn('id', $data['item_ids'])
            ->get();

        $nb_updated = 0;

        foreach ($recurrences as $recurrence) {
            if (!empty($recurrence->active)) {
                $recurrence->active = 0;
                $recurrence->ends_at = null;
            } else {
                $recurrence->active = 1;
            }

            $nb_updated += $recurrence->save();
        }

        return response()->json([
            'updated' => $nb_updated,
            'view' => view('recurrences.lists', [
                'recurrences' => TransacRecurringPattern::getList(),
                'past_recurrences' => TransacRecurringPattern::getList(['past' => true]),
                'inactive_recurrences' => TransacRecurringPattern::getList(['active' => 0]),
            ])->render()
        ]);
    }
}
