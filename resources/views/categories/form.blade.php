<div id="categories-form-wrapper">
    <h1>
        {{ !empty($category) ? "Modifier {$category->name}" : 'Ajouter une catégorie' }}
    </h1>

    <div class="w-50 mt-5 mx-auto">
        <form id="categories-form">
            <input type="hidden" name="id" value="{{ !empty($category) ? $category->id : '' }}" />

            <div class="form-control form-floating">
                <input
                    id="categ-name"
                    type="text"
                    name="name"
                    value="{{ !empty($category) ? $category->name : '' }}"
                    maxlength="100"
                    autocomplete="off"
                    data-protonpass-ignore="true"
                    required
                />

                <label for="categ-name" class="mb-3">Nom</label>
            </div>

            <div class="form-control">
                <label for="categ-color">Couleur</label>

                <input
                    id="categ-color"
                    name="color"
                    type="color"
                    value="{{ $category->color ? $category->color : "#563d7c" }}"
                    title="Choose your color"
                    maxlength="9"
                />
            </div>

            <div class="form-control form-floating">
                <textarea
                    id="categ-description"
                    name="description"
                    maxlength="255"
                    rows="6"
                >{{ $category->description }}</textarea>

                <label for="categ-description" class="mb-3">Description</label>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="me-2 btn btn-success">Sauvegarder</button>
                <a href="{{ route('categ_index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
