<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Label;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Validator;

class BeneficiariesController extends Controller
{
    public function index()
    {
        return view('beneficiaries.index', [
            'beneficiaries' => Beneficiary::getList(),
            'categories' => Category::getDropdownList(),
            'labels' => Label::getDropdownList(),
        ]);
    }

    public function filter(Request $request)
    {
        return view('beneficiaries.list', [
            'beneficiaries' => Beneficiary::getList($request->input('filters')),
            'categories' => Category::getDropdownList(),
            'labels' => Label::getDropdownList(),
        ]);
    }

    public function get($id)
    {
        return response()->json(['item' => Beneficiary::getOne($id)]);
    }

    /**
     * store
     *
     * @param  Request $request
     * @return JsonResponse
     */
    public function store (Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Beneficiary::class],
            'raw_name' => [
                'required',
                'max:255',
                !empty($request->input('id'))
                    ? Rule::unique(Beneficiary::class)->ignore($request->input('id'))
                    : 'unique:' . Beneficiary::class
            ],
            'pretty_name' => ['nullable', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:' . Category::class . ',id'],
            'label_id' => ['nullable', 'integer', 'exists:' . Label::class . ',id'],
            'description' => ['nullable', 'max:255'],
            'non_recurring' => ['nullable', Rule::in(['on'])],
        ]);

        if (!empty($data['id'])) {
            $benef_id = $data['id'];
            $benef = Beneficiary::find($data['id']);
            $benef->raw_name = trim($data['raw_name']);
            $benef->pretty_name = trim($data['pretty_name']);
            $benef->category_id = $data['category_id'];
            $benef->label_id = $data['label_id'];
            $benef->description = trim($data['description']);
            $benef->non_recurring = !empty($data['non_recurring']);
            $benef->save();
        } else {
            $benef_id = Beneficiary::create([
                'raw_name' => trim($data['raw_name']),
                'pretty_name' => trim($data['pretty_name']),
                'category_id' => $data['category_id'],
                'label_id' => $data['label_id'],
                'description' => trim($data['description']),
                'non_recurring' => !empty($data['non_recurring']),
            ]);
        }

        return response()->json(['updated' => $benef_id]);
    }

    public function storeInBulk(Request $request)
    {
        $data = $request->validate([
            'item_ids.*' => Rule::forEach(function () {
                return [
                    Rule::exists(Beneficiary::class, 'id'),
                ];
            }),
            'pretty_name' => ['nullable', 'max:255'],
            'category_id' => ['nullable', 'exists:' . Category::class . ',id'],
            'label_id' => ['nullable', 'exists:' . Label::class . ',id'],
            'non_recurring' => ['nullable', Rule::in(['on'])],
        ]);

        $update_data = [];

        if (!empty($data['category_id'])) {
            $update_data['category_id'] = $data['category_id'];
        }

        if (!empty($data['label_id'])) {
            $update_data['label_id'] = $data['label_id'];
        }

        if (!empty($data['pretty_name'])) {
            $update_data['pretty_name'] = $data['pretty_name'];
        }

        if (!empty($data['non_recurring'])) {
            $update_data['non_recurring'] = 1;
        }

        if (empty($data)) {
            return response()->json(['message' => 'Rien à mettre à jour.'], 406);
        }

        $updated = Beneficiary::whereIn('id', $data['item_ids'])
            ->update($update_data);

        return response()->json(['updated' => $updated]);
    }

    /**
     * @param Request $request
     * @param int|null $benef_id
     * @return JsonResponse
     */
    public function syncCategories(Request $request, ?int $benef_id = null): JsonResponse
    {
        if (!empty($benef_id)) {
            \validator(\request()->route()->parameters(), [
                'benef_id' => ['required', 'integer', 'exists:' . Beneficiary::class . ',id'],
            ])->validate();

            $data['item_ids'] = [$benef_id];
        } else {
            $data = $request->validate([
                'item_ids.*' => [
                    Rule::forEach(function () {
                        return [
                            'required',
                            'integer',
                            Rule::exists(Beneficiary::class, 'id'),
                        ];
                    }),
                ],
            ]);
        }

        $beneficiaries = Beneficiary::query()
            ->select(['id', 'category_id', 'raw_name'])
            ->whereIn('id', $data['item_ids'])
            ->get();

        $nb_updated = 0;

        foreach ($beneficiaries as $beneficiary) {
            if (empty($beneficiary->category_id)) {
                continue;
            }

            $nb_updated += Transaction::where('beneficiary_id', $beneficiary->id)
                ->update(['category_id' => $beneficiary->category_id]);
        }
        return response()->json(['updated' => $nb_updated]);
    }

    /**
     * @param Request $request
     * @param int|null $benef_id
     * @return JsonResponse
     */
    public function syncLabels(Request $request, ?int $benef_id = null): JsonResponse
    {
        if (!empty($benef_id)) {
            \validator(\request()->route()->parameters(), [
                'benef_id' => ['required', 'integer', 'exists:' . Beneficiary::class . ',id'],
            ])->validate();

            $data['item_ids'] = [$benef_id];
        } else {
            $data = $request->validate([
                'item_ids.*' => [
                    Rule::forEach(function () {
                        return [
                            'required',
                            'integer',
                            Rule::exists(Beneficiary::class, 'id'),
                        ];
                    }),
                ],
            ]);
        }

        $beneficiaries = Beneficiary::query()
            ->select(['id', 'category_id', 'label_id', 'raw_name'])
            ->whereIn('id', $data['item_ids'])
            ->get();

        $nb_updated = 0;

        foreach ($beneficiaries as $beneficiary) {
            if (empty($beneficiary->label_id)) {
                continue;
            }

            $transactions = Transaction::whereHas('beneficiary', function (Builder $query) use ($beneficiary) {
                    $query->where('beneficiary_id', $beneficiary->id);
                })
                ->whereDoesntHave('labels', function (Builder $query) use ($beneficiary) {
                    $query->where('labels.id', $beneficiary->label_id);
                })
                ->get();

            foreach ($transactions as $transaction) {
                $transaction->labels()->attach($beneficiary->label_id);
                $transaction->save();
                $nb_updated++;
            }
        }
        return response()->json(['updated' => $nb_updated]);
    }

    /**
     * @param int $benef_id
     * @return JsonResponse
     */
    public function delete(int $benef_id)
    {
        $benef = Beneficiary::query()
            ->whereDoesntHave('transactions')
            ->first();

        if (empty($benef_id) || empty($benef)) {
            return response()->json(['message' => 'Bénéficiaire introuvable ou lié à une transaction.'], 422);
        }

        return response()->json(['updated' => Beneficiary::where('id', $benef_id)->delete()]);
    }
}
