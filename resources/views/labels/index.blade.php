<div id="labels-wrapper">
    <h1>Labels</h1>

    <div class="d-flex justify-content-end mx-auto w-75">
        <a
            class="btn btn-sm btn-success"
            href="{{ route('label_form') }}"
        >
            <i class="me-1 fas fa-plus-circle" /> Créer
        </a>
    </div>

    <table class="table table-striped table-bordered mt-3 mx-auto w-75">
        <thead>
            <tr>
                <th style="width: 12rem">Nom</th>
                <th>Description</th>
                <th style="width: 4rem"></th>
            </tr>
        </thead>

        <tbody>
            @forelse ($labels as $label)
                <tr>
                    <td>{{ $label->name }}</td>
                    <td>{{ $label->description }}</td>

                    <td class="text-center">
                        <a
                            class="btn btn-sm btn-primary"
                            href="{{ route('label_form', ['label_id' => $label->id]) }}"
                        >
                            <i class="fas fa-pencil" />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3">
                        <i class="fas fa-ban me-1"></i><span class="text-muted fst-italic">Aucun résultat</span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
