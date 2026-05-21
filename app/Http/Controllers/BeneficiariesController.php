<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
                'nullable',
                'max:255',
                'unique:' . Beneficiary::class,
            ],
            'pretty_name' => ['nullable', 'max:255'],
            'category_id' => ['nullable', 'integer', 'exists:' . Category::class . ',id'],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $benef_id = $data['id'];
            $benef = Beneficiary::find($data['id']);
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

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function syncCategories(Request $request): JsonResponse
    {
        $benefs = Beneficiary::query()
            ->select(['id', 'category_id'])
            ->whereIn('id', $request->input('benef_ids'))
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
    public function syncCategoriesInBulk(Request $request, int $benef_id, int $categ_id): JsonResponse
    {
        $transactions = Transaction::whereIn('beneficiary_id', $benef_id)
            ->update(['category_id' => $categ_id]);
        return response()->json(['updated' => $transactions]);
    }

    public function storeInBulk(Request $request)
    {
        $data = $request->validate([
            'benef_ids.*' => Rule::forEach(function ($value, string $attribute) {
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

        $updated = Beneficiary::whereIn('id', $data['benef_ids'])
            ->update($update_data);

        return response()->json(['updated' => $updated]);
    }

    /**
     * @param int $benef_id
     * @return void
     */
    public function delete(int $benef_id)
    {

    }
}
