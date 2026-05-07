<?php

namespace App\Http\Controllers;

use App\Models\Beneficiary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class BeneficiariesController extends Controller
{
    public function get($id)
    {
        return response()->json(['beneficiary' => Beneficiary::getOne($id)]);
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
            'notes' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $benef_id = $data['id'];
            $benef = Beneficiary::find($data['id']);
            $benef->pretty_name = trim($data['pretty_name']);
            $benef->notes = trim($data['notes']);
            $benef->save();
        } else {
            $benef_id = Beneficiary::create([
                'raw_name' => trim($data['raw_name']),
                'pretty_name' => trim($data['pretty_name']),
                'notes' => trim($data['notes']),
            ]);
        }

        return response()->json(['updated' => $benef_id]);
    }
}
