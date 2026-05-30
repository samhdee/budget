<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Throwable;

class CategoriesController extends Controller
{
    public function index(): View
    {
        return view('categories.index', ['categories' => Category::query()
            ->select(['id', 'appellation', 'color'])
            ->withCount('transactions as nb_transactions')
            ->orderBy('appellation')
            ->get()
        ]);
    }

    /**
     * create
     *
     * @param $categ_id
     * @return JsonResponse
     */
    public function get($categ_id): JsonResponse
    {
        return response()->json(['item' => Category::query()
            ->select(['id', 'appellation', 'color', 'description'])
            ->where('id', $categ_id)
            ->firstOrFail()
        ]);
    }

    /**
     * store
     *
     * @param Request $request
     * @return JsonResponse
     * @throws Throwable
     */
    public function store (Request $request): JsonResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Category::class],
            'appellation' => [
                'required',
                'max:100',
                !empty($request->input('id'))
                    ? Rule::unique(Category::class)->whereNull('deleted_at')->ignore($request->input('id'))
                    : 'unique:' . Category::class
            ],
            'color' => ['nullable', 'hex_color'],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $category = Category::find($data['id']);
            $category->appellation = trim($data['appellation']);
            $category->color = $data['color'];
            $category->description = trim($data['description']);
            $category->save();
        } else {
            Category::create([
                'appellation' => trim($data['appellation']),
                'color' => $data['color'],
                'description' => trim($data['description']),
            ]);
        }

        return response()->json([
            'updated' => true,
            'view' => view('categories.list', [
                'categories' => Category::query()
                    ->select(['id', 'appellation', 'color'])
                    ->orderBy('appellation')
                    ->get()
                ])
                ->render()
        ]);
    }

    /**
     * @throws Throwable
     */
    public function delete(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:' . Category::class],
        ]);

        $transactions = Transaction::query()
            ->select('id')
            ->where('category_id', $data['id'])
            ->get();

        if ($transactions->isNotEmpty()) {
            Transaction::whereIn('id', $transactions->pluck('id'))
                ->update(['category_id' => null]);
        }

        return response()->json([
            'deleted' => Category::where('id', $data['id'])->delete(),
            'view' => view('categories.list', ['categories' => Category::query()
                ->select(['id', 'appellation', 'color', 'description'])
                ->orderBy('appellation')
                ->get()
            ])->render()
        ]);
    }
}
