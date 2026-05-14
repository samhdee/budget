<table class="mt-3 mx-auto w-50 table table-striped table-bordered align-middle">
    <thead>
        <tr>
            <th style="width: 12rem">Nom</th>
            <th>Description</th>
            <th style="width: 5rem">Couleur</th>
            <th style="width: 4rem"></th>
        </tr>
    </thead>

    <tbody>
        @forelse ($categories as $category)
            <tr>
                <td>{{ $category->appellation }}</td>
                <td>{{ $category->description }}</td>

                <td>
                    <input
                        type="color"
                        class="mx-auto rounded-1"
                        value="{{ !empty($category->color) ? $category->color : '#000000' }}"
                        disabled
                    />
                </td>

                <td class="text-center">
                    <a
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-categ-form"
                        data-action="edit"
                        data-url="{{ route('categ_get', $category->id) }}"
                        data-item_id="{{ $category->id }}"
                        data-type="catégorie"
                    >
                        <i class="fas fa-pencil"></i>
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="4" class="text-center text-muted fst-italic">
                    <i class="fas fa-ban"></i> <span class="text-muted fst-italic"></span>
                    Aucun résultat
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
