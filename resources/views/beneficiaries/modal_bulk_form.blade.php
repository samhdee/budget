@php use App\Models\Category; @endphp

<div
    id="modal-benef-bulk-form"
    class="modal fade modal-bulk-form"
    tabindex="-1"
    aria-labelledby="benef-form-label"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="benef-bulk-edit-form" class="mt-4" method="POST" action="{{ route('benef_bulk_store') }}">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="benef-form-label"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="d-none mb-3 alert alert-danger"></div>

                    <div class="form-floating form-field">
                        <input
                            id="benef-pretty_name"
                            type="text"
                            name="pretty_name"
                            class="form-control"
                            maxlength="255"
                            autocomplete="off"
                        />

                        <label for="benef-pretty_name">Nom joli</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <select id="benef-bulk-category-id" name="category_id" class="form-select" required>
                            <option value=""></option>

                            @php /** @var Category[] $category */ @endphp
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->appellation }}
                                </option>
                            @endforeach
                        </select>

                        <label for="benef-bulk-category-id">Catégorie par défaut</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="benef-form-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
