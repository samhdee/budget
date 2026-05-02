<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TransactionsController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        return view('transactions.index', [
            'transactions' => Transaction::getList(),
            'beneficiaries' => Beneficiary::getList(),
        ]);
    }

    public function filter(Request $request)
    {
        // @TODO: Ajouter des règles de validation
        return view('transactions.list', ['transactions' => Transaction::getList($request->input('filters'))]);
    }

    public function get($id)
    {
        return response()->json(['transaction' => Transaction::getOne($id)]);
    }

    /**
     * store
     *
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Transaction::class],
            'amount' => ['required', 'decimal:2'],
            'occurred_at' => ['required', 'date'],
        ]);

        if (!empty($data['id'])) {
            $transac_id = $data['id'];
            $transaction = Transaction::find($data['id']);
            $transaction->occurred_at = $data['occurred_at'];
            $transaction->amount = $data['amount'];
            $transaction->save();
        } else {
            $transac_id = Transaction::create([
                'amount' => $data['amount'],
                'occurred_at' => $data['occurred_at'],
            ]);
        }

        return to_route('transac_index', ['updated' => $transac_id]);
    }
}
