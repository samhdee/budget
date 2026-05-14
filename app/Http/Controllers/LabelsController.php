<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\RedirectResponse;

class LabelsController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        return view('labels.index', ['labels' => Label::query()
            ->select(['id', 'appellation', 'color', 'description'])
            ->orderBy('appellation')
            ->get()
        ]);
    }

    /**
     * create
     *
     * @param $label_id
     * @return JsonResponse
     */
    public function get($label_id): JsonResponse
    {
        return response()->json(['item' => Label::query()
            ->select(['appellation', 'color', 'description'])
            ->where('id', $label_id)
            ->firstOrFail()
        ]);
    }

    /**
     * store
     *
     * @param  Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|View
     */
    public function store (Request $request): View
    {
        $data = $request->validate([
            'id' => ['nullable', 'integer', 'exists:' . Label::class],
            'appellation' => [
                'required',
                'max:100',
                !empty($request->input('id'))
                    ? Rule::unique(Label::class)->whereNull('deleted_at')->ignore($request->input('id'))
                    : 'unique:' . Label::class
            ],
            'color' => ['nullable', 'hex_color'],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $label_id = $data['id'];
            // @FIXME: gestion d’erreur
            $label = Label::findOrFail($data['id']);
            $label->appellation = trim($data['appellation']);
            $label->color = trim($data['color']);
            $label->description = trim($data['description']);
            $label->save();
        } else {
            $label_id = Label::create([
                'appellation' => trim($data['appellation']),
                'color' => trim($data['color']),
                'description' => trim($data['description']),
            ]);
        }

        return view('labels.list', ['labels' => Label::query()
            ->select(['id', 'appellation', 'color'])
            ->orderBy('appellation')
            ->get()
        ]);
    }
}
