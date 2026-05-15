<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index(): View
    {
        return view('categories.index', ['categories' => Category::query()
            ->select(['id', 'appellation', 'color'])
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
     * @return View
     */
    public function store (Request $request): View
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

        return view('categories.list', ['categories' => Category::query()
            ->select(['id', 'appellation', 'color'])
            ->orderBy('appellation')
            ->get()
        ]);
    }

    public function delete()
    {

    }
}
