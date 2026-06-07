<?php

namespace App\Http\Controllers;

use App\Enums\TransactionType;
use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Label;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
            'categories' => Category::getDropdownList(),
            'labels' => Label::getDropdownList(),
        ]);
    }

    public function filter(Request $request)
    {
        // @TODO: Ajouter des règles de validation
        return view('transactions.list', [
            'transactions' => Transaction::getList($request->input('filters')),
            'beneficiaries' => Beneficiary::getDropdownList(),
            'categories' => Category::getDropdownList(),
            'labels' => Label::getDropdownList(),
        ]);
    }

    public function get($id)
    {
        return response()->json(['item' => Transaction::getOne($id)]);
    }

    /**
     * bulkStore
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function bulkStore(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item_ids.*' => Rule::forEach(function ($value, string $attribute) {
                return [
                    Rule::exists(Transaction::class, 'id'),
                ];
            }),
            'type' => ['nullable', Rule::enum(TransactionType::class)],
            'category_id' => ['nullable', 'exists:' . Category::class . ',id'],
            'label_id' => ['nullable', 'exists:' . Label::class . ',id'],
            'beneficiary_id' => ['nullable', 'exists:' . Beneficiary::class . ',id'],
        ]);

        if (!empty($data['type'])) {
            $update_data['type'] = $data['type'];
        }

        if (!empty($data['category_id'])) {
            $update_data['category_id'] = $data['category_id'];
        }

        if (!empty($data['beneficiary_id'])) {
            $update_data['beneficiary_id'] = $data['beneficiary_id'];
        }

        if (empty($update_data) && empty($data['label_id'])) {
            return response()->json(['message' => 'Rien à mettre à jour']);
        }

        $nb_updated = Transaction::whereIn('id', $data['item_ids'])
            ->update($update_data);

        if (!empty($data['label_id'])) {
            $label = Label::find($data['label_id']);
            $label->transactions()->attach($data['item_ids']);
            $label->save();
        }

        return response()->json(['updated' => $nb_updated]);
    }

    /**
     * store
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Transaction::class],
            'amount' => ['required', 'decimal:2'],
            'type' => ['required', Rule::enum(TransactionType::class)],
            'occurred_at' => ['required', 'date'],
            'beneficiary_id' => ['nullable', 'exists:' . Beneficiary::class . ',id'],
            'category_id' => ['nullable', 'exists:' . Category::class . ',id'],
            'labels.*' => Rule::forEach(function () {
                return [
                    Rule::exists(Label::class, 'id'),
                ];
            }),
            'labels' => ['nullable', 'exists:' . Label::class . ',id'],
            'notes' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $transac_id = $data['id'];
            $transaction = Transaction::find($data['id']);
            $transaction->occurred_at = $data['occurred_at'];
            $transaction->amount = $data['amount'];
            $transaction->type = $data['type'];
            $transaction->beneficiary_id = $data['beneficiary_id'] ?? null;
            $transaction->category_id = $data['category_id'] ?? null;
            $transaction->notes = $data['notes'] ?? null;

            $transaction->labels()->detach();

            if (!empty($data['labels'])) {
                $transaction->labels()->attach($data['labels']);
            }

            $transaction->save();
        } else {
            $transaction = Transaction::create([
                'amount' => $data['amount'],
                'occurred_at' => $data['occurred_at'],
                'type' => $data['type'],
                'beneficiary_id' => $data['beneficiary_id'] ?? null,
                'category_id' => $data['category_id'] ?? null,
                'notes' => $data['notes'] ?? null,
            ]);

            if (!empty($data['labels'])) {
                $transaction->labels()->attach($data['labels']);
            }

            $transac_id = $transaction->id;
        }

        return response()->json(['updated' => $transac_id]);
    }

    public function delete($transac_id)
    {
        \validator(\request()->route()->parameters(), [
            'id' => ['required', 'integer', 'exists:' . Transaction::class],
        ])->validate();

        return response()->json(['deleted' => Transaction::where('id', $transac_id)->delete()]);
    }

    public function bulkDelete(Request $request)
    {
        $data = $request->validate([
            'item_ids.*' => Rule::forEach(function () {
                return [
                    Rule::exists(Transaction::class, 'id'),
                ];
            }),
        ]);

        return response()->json(['deleted' => Transaction::whereIn('id', $data['item_ids'])->delete()]);
    }
}
