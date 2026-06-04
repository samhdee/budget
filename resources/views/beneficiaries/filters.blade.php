@php use App\Enums\TransactionType; @endphp

<div class="d-flex justify-content-between align-items-center">
    <div
        id="benefs-filter-wrapper"
        class="filters-wrapper d-flex gap-3"
        data-url="{{ route('benef_filter') }}"
        data-target="#benef-list-wrapper"
    >
        <div class="filter-wrapper with-reset form-floating">
            <input
                id="benef-filter-name"
                type="text"
                name="either_name"
                class="form-control"
                size="30"
            />

            <label for="benef-filter-benef">Bénéficiaire</label>

            <button
                type="button"
                class="filter-reset d-none btn btn-sm btn-close-white"
                data-target="#benef-filter-name"
            >
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>

        <div class="filter-wrapper form-floating">
            <select id="benef-filter-transac" name="with_transac" class="form-select" style="width: 130px">
                <option value="">Tout</option>
                <option value="true">Avec</option>
                <option value="false">Sans</option>
            </select>

            <label for="benef-filter-transac">Transactions</label>
        </div>

        <div class="filter-wrapper form-floating">
            <select id="benef-filter-categ" name="category_id" class="form-select">
                <option value=""></option>

                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->appellation }}</option>
                @endforeach
            </select>

            <label for="benef-filter-categ">Caégorie par défaut</label>
        </div>

        <div class="filter-wrapper form-floating">
            <select id="benef-filter-label" name="label_id" class="form-select">
                <option value=""></option>

                @foreach($labels as $label)
                    <option value="{{ $label->id }}">{{ $label->appellation }}</option>
                @endforeach
            </select>

            <label for="benef-filter-label">Label par défaut</label>
        </div>

        <div>
            <button type="button" class="ms-2 btn btn-sm btn-danger all-filter-reset">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    <div>
        <button
            type="button"
            class="btn btn-sm btn-success"
            data-bs-toggle="modal"
            data-bs-target="#modal-benef-form"
            data-action="create"
            data-type="bénéficiaire"
        >
            <i class="fas fa-plus-circle"></i> Créer
        </button>
    </div>
</div>
