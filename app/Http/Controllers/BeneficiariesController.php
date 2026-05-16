<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use App\Models\Category;
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
}
