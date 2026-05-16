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

            <label for="transac-filter-benef">Bénéficiaire</label>

            <button
                type="button"
                class="filter-reset d-none btn btn-sm btn-close-white"
                data-target="#benef-filter-name"
            >
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>

        <div>
            <button type="button" class="btn btn-sm btn-danger all-filter-reset">
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
