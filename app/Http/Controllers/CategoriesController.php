<?php

namespace App\Http\Controllers;

use App\Models\CategoryModel;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriesController extends Controller
{
    public function index(): View
    {
        return view('categories.index', ['categories' => CategoryModel::all()]);
    }

    /**
     * create
     *
     * @param  mixed $request
     * @return View
     */
    public function create(Request $request): View
    {
        $category = null;

        if (!empty($request->input('cat_id'))) {
            $category = CategoryModel::find($request->input('cat_id'));
        }

        return view('categories.form', ['category' => $category]);
    }

    /**
     * store
     *
     * @param  Request $request
     * @return void
     */
    public function store (Request $request) {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . CategoryModel::class],
            'name' => [
                'required',
                'max:100',
                !empty($request->input('id'))
                    ? Rule::unique(CategoryModel::class)->whereNull('deleted_at')->ignore($request->input('id'))
                    : 'unique:' . CategoryModel::class
            ],
            'color' => ['nullable', 'max:9', 'hex_color'],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $cat_id = $data['id'];
            $category = CategoryModel::find($data['id']);
            $category->name = trim($data['name']);
            $category->color = $data['color'];
            $category->description = trim($data['description']);
            $category->save();
        } else {
            $cat_id = CategoryModel::create([
                'name' => trim($data['name']),
                'color' => $data['color'],
                'description' => trim($data['description']),
            ]);
        }

        return to_route('cat_index', ['updated' => $cat_id]);
    }
}
