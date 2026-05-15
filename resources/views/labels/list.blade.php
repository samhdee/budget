<table class="mt-3 mx-auto w-50 table table-striped table-bordered align-middle">
    <thead>
        <tr>
            <th style="width: 12rem">Nom</th>
            <th style="width: 5rem;">Couleur</th>
            <th>Description</th>
            <th style="width: 6rem"></th>
        </tr>
    </thead>

    <tbody>
        @forelse ($labels as $label)
            <tr>
                <td>{{ $label->appellation }}</td>

                <td>
                    <input
                        type="color"
                        class="mx-auto rounded-1"
                        value="{{ !empty($label->color) ? $label->color : '#000000' }}"
                        disabled
                    />
                </td>

                <td>{{ $label->description }}</td>

                <td class="text-center">
                    <a
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-label-form"
                        data-action="edit"
                        data-type="étiquette"
                        data-url="{{ route('label_get', $label->id) }}"
                    >
                        <i class="fas fa-pencil"></i>
                    </a>

                    <a
                        class="ms-1 btn btn-sm btn-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-label-delete"
                        data-url="{{ route('label_get', $label->id) }}"
                        data-action="delete"
                        data-type="étiquette"
                    >
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted fst-italic">
                    <i class="fas fa-ban me-1"></i> Aucun résultat
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
