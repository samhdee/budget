<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Transaction;
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
        ]);
    }

    public function filter(Request $request)
    {
        return view('beneficiaries.list', [
            'beneficiaries' => Beneficiary::getList($request->input('filters')),
            'categories' => Category::getDropdownList(),
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
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $benef_id = $data['id'];
            $benef = Beneficiary::find($data['id']);
            $benef->raw_name = trim($data['raw_name']);
            $benef->pretty_name = trim($data['pretty_name']);
            $benef->category_id = $data['category_id'];
            $benef->description = trim($data['description']);
            $benef->save();
        } else {
            $benef_id = Beneficiary::create([
                'raw_name' => trim($data['raw_name']),
                'pretty_name' => trim($data['pretty_name']),
                'category_id' => $data['category_id'],
                'description' => trim($data['description']),
            ]);
        }

        return response()->json(['updated' => $benef_id]);
    }

    public function storeInBulk(Request $request)
    {
        $data = $request->validate([
            'item_ids.*' => Rule::forEach(function ($value, string $attribute) {
                return [
                    Rule::exists(Beneficiary::class, 'id'),
                ];
            }),
            'category_id' => ['required', 'exists:' . Category::class . ',id'],
            'pretty_name' => ['nullable', 'max:255'],
        ]);

        $update_data = [
            'category_id' => $data['category_id'],
        ];

        if (!empty($data['pretty_name'])) {
            $update_data['pretty_name'] = $data['pretty_name'];
        }

        $updated = Beneficiary::whereIn('id', $data['item_ids'])
            ->update($update_data);

        return response()->json(['updated' => $updated]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function syncCategories(Request $request): JsonResponse
    {
        \validator(\request()->route()->parameters(), [
            'id' => ['required', 'integer', 'exists:' . Beneficiary::class],
        ])->validate();

        $benefs = Beneficiary::query()
            ->select(['id', 'category_id'])
            ->where('id', $request->input('id'))
            ->get();

        $nb_updated = 0;

        foreach ($benefs as $benef) {
            if (empty($benef->category_id)) {
                continue;
            }

            $nb_updated += Transaction::where('beneficiary_id', $benef->id)
                ->update(['category_id' => $benef->category_id]);
        }

        return response()->json(['updated' => $nb_updated]);
    }

    /**
     * @param Request $request
     * @param int $benef_id
     * @param int $categ_id
     * @return JsonResponse
     */
    public function syncCategoriesInBulk(Request $request): JsonResponse
    {
        $data = $request->validate([
            'item_ids.*' => Rule::forEach(function ($value, string $attribute) {
                return [
                    Rule::exists(Transaction::class, 'id'),
                ];
            }),
        ]);

        $beneficiaries = Beneficiary::query()
            ->select(['id', 'category_id'])

        $transactions = Transaction::whereIn('beneficiary_id', $data['item_ids'])
            ->update(['category_id' => $categ_id]);
        return response()->json(['updated' => $transactions]);
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

        return response()->json(['updated' => $benef->delete()]);
    }
}
