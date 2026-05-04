<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Beneficiary;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
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
            'beneficiaries' => Beneficiary::getDropdownList(),
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
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        // @TODO: Ajouter une vérif sur l'unicité de benef pretty_name
        // @TODO: Ajouter une vérif sur le type
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Transaction::class],
            'amount' => ['required', 'decimal:2'],
            'type' => ['required', Rule::enum(TransactionType::class)],
            'occurred_at' => ['required', 'date'],
            'beneficiary_id' => ['required', 'exists:' . Beneficiary::class . ',id'],
            'notes' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $transac_id = $data['id'];
            $transaction = Transaction::find($data['id']);
            $transaction->occurred_at = $data['occurred_at'];
            $transaction->amount = $data['amount'];
            $transaction->type = $data['type'];
            $transaction->notes = $data['notes'];
            $transaction->save();
        } else {
            $transac_id = Transaction::create([
                'amount' => $data['amount'],
                'occurred_at' => $data['occurred_at'],
                'type' => $data['type'],
                'notes' => $data['notes'],
            ]);
        }

        return response()->json(['updated' => $transac_id]);
    }
}
