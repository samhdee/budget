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

        <div class="filter-wrapper">
            <input
                id="benef-filter-with-transac-yes"
                type="radio"
                name="with_transac"
                class="me-1"
                value="true"
            />
            <label for="benef-filter-with-transac-yes" class="form-check-label">Avec transaction</label>
        </div>

        <div class="filter-wrapper">
            <input
                id="benef-filter-with-transac-no"
                type="radio"
                name="with_transac"
                class="ms-2 me-1"
                value="false"
            />
            <label for="benef-filter-with-transac-no" class="form-check-label">Sans</label>
        </div>

        <div class="filter-wrapper">
            <input
                id="benef-filter-with-transac-all"
                type="radio"
                name="with_transac"
                class="ms-2 me-1"
                value="all"
            />
            <label for="benef-filter-with-transac-all" class="form-check-label">Tout</label>
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
