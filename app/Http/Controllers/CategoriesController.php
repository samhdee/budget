<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index(): View
    {
        return view('categories.index', ['categories' => Category::all()]);
    }

    /**
     * create
     *
     * @param  mixed $request
     * @return View
     */
    public function form(Request $request): View
    {
        $category = null;

        if (!empty($request->input('cat_id'))) {
            $category = Category::find($request->input('cat_id'));
        }

        return view('categories.form', ['category' => $category]);
    }

    /**
     * store
     *
     * @param  Request $request
     * @return RedirectResponse
     */
    public function store (Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Category::class],
            'name' => [
                'required',
                'max:100',
                !empty($request->input('id'))
                    ? Rule::unique(Category::class)->whereNull('deleted_at')->ignore($request->input('id'))
                    : 'unique:' . Category::class
            ],
            'color' => ['nullable', 'max:9', 'hex_color'],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $cat_id = $data['id'];
            $category = Category::find($data['id']);
            $category->name = trim($data['name']);
            $category->color = $data['color'];
            $category->description = trim($data['description']);
            $category->save();
        } else {
            $cat_id = Category::create([
                'name' => trim($data['name']),
                'color' => $data['color'],
                'description' => trim($data['description']),
            ]);
        }

        return to_route('categ_index', ['updated' => $cat_id]);
    }
}
