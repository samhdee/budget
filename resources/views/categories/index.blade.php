<div id="categories-wrapper">
    <h1>Coucou c&rsquo;est les catégoRIRES</h1>

    <div class="mx-auto w-75 d-flex justify-content-end">
        <a class="btn btn-sm btn-success" href="{{ route('categ_form') }}">
            <i class="me-1 fas fa-plus-circle" /> Créer
        </a>
    </div>

    <table class="mt-3 mx-auto w-75 table table-striped table-bordered">
        <thead>
            <tr>
                <th style="width: 12rem">Nom</th>
                <th>Description</th>
                <th style="width: 5rem">Couleur</th>
                <th style="width: 4rem"></th>
            </tr>
        </thead>

        <tbody>
            @forelse ($categories as $categoy)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>

                    <td class="text-center">
                        <div class="form-control">
                            <input>
                                type="color"
                                value="{{ !empty($category->color) ? $category->color : '#000000' }}"
                                disabled
                            />
                        </div>
                    </td>

                    <td class="text-center">
                        <a
                            class="btn btn-sm btn-primary"
                            href="{{ route('categ_form', ['cat_id' => $category->id ]) }}"
                        >
                            <FontAwesomeIcon icon={faPencil} />
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4">
                        <i class="fas fa-ban"></i> <span class="text-muted fst-italic"></span>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
