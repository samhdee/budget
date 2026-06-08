<?php

namespace App\Http\Controllers;

use App\Models\Label;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Throwable;

class LabelsController extends Controller
{
    /**
     * index
     *
     * @return View
     */
    public function index(): View
    {
        return view('labels.index', ['labels' => Label::getList()]);
    }

    /**
     * create
     *
     * @param $label_id
     * @return JsonResponse
     */
    public function get($label_id): JsonResponse
    {
        return response()->json(['item' => Label::getOne($label_id)]);
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
            'id' => ['nullable', 'integer', 'exists:' . Label::class],
            'appellation' => [
                'required',
                'max:100',
                !empty($request->input('id'))
                    ? Rule::unique(Label::class)->whereNull('deleted_at')->ignore($request->input('id'))
                    : 'unique:' . Label::class
            ],
            'goal' => ['nullable', 'integer'],
            'description' => ['nullable', 'max:255'],
        ]);

        if (!empty($data['id'])) {
            $label_id = $data['id'];
            // @FIXME: gestion d’erreur
            $label = Label::findOrFail($data['id']);
            $label->appellation = trim($data['appellation']);
            $label->goal = !empty($data['goal']) ? trim($data['goal']) : null;
            $label->description = trim($data['description']);
            $label->save();
        } else {
            $label_id = Label::create([
                'appellation' => trim($data['appellation']),
                'goal' => !empty($data['goal']) ? trim($data['goal']) : null,
                'description' => trim($data['description']),
            ]);
        }

        return response()->json([
            'updated' => $label_id,
            'view' => view('labels.list', ['labels' => Label::getList()])->render()
        ]);
    }

    /**
     * @throws Throwable
     */
    public function delete(Request $request)
    {
        $data = $request->validate([
            'id' => ['required', 'integer', 'exists:' . Label::class],
        ]);

        return response()->json([
            'deleted' => Label::where('id', $data['id'])->delete(),
            'view' => view('labels.list', ['labels' => Label::getList()])->render()
        ]);
    }
}
