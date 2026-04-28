<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LabelsController extends Controller
{
    /**
     * index
     *
     * @return Response
     */
    public function index(): View
    {
        return view('labels.index', ['labels' => Label::all()]);
    }

    /**
     * create
     *
     * @param  mixed $request
     * @return void
     */
    public function form(Request $request): View
    {
        $label = null;

        if (!empty($request->input('label_id'))) {
            $label = Label::find($request->input('label_id'));
        }

        return view('labels.form', ['label' => $label]);
    }

    /**
     * store
     *
     * @param  Request $request
     * @return void
     */
    public function store (Request $request): RedirectResponse
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Label::class],
            'name' => [
                'required',
                'max:100',
                !empty($request->input('id'))
                    ? Rule::unique(Label::class)->whereNull('deleted_at')->ignore($request->input('id'))
                    : 'unique:' . Label::class
            ],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $label_id = $data['id'];
            $label = Label::find($data['id']);
            $label->name = trim($data['name']);
            $label->description = trim($data['description']);
            $label->save();
        } else {
            $label_id = Label::create([
                'name' => trim($data['name']),
                'description' => trim($data['description']),
            ]);
        }

        return to_route('labels_index', ['updated' => $label_id]);
    }
}
