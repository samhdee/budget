@php
    use App\Enums\TransactionType;
    use App\Models\Category;
@endphp

<div class="d-flex justify-content-between align-items-center">
    <div
        id="transactions-filter-wrapper"
        class="filters-wrapper d-flex gap-3"
        data-url="{{ route('transac_filter') }}"
        data-target="#transac-list-wrapper"
    >
        <div class="form-floating">
            <select id="transac-filter-type" name="type" class="form-select">
                <option value="">Tous</option>

                @foreach(TransactionType::cases() as $transac_type)
                    <option value="{{ $transac_type->name }}">
                        {{ getTransactionTypeLabel($transac_type->name) }}
                    </option>
                @endforeach
            </select>

            <label for="transac-filter-type">Type</label>
        </div>

        <div class="form-floating">
            <select id="transac-filter-category" name="category_id" class="form-select">
                <option value="">Tous</option>

                @php /** @var Category $category */ @endphp
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->appellation }}
                    </option>
                @endforeach
            </select>

            <label for="transac-filter-type">Type</label>
        </div>

        <div class="filter-wrapper with-reset form-floating">
            <input
                id="transac-filter-benef"
                type="text"
                name="benef_name"
                class="form-control"
                size="30"
            />

            <label for="transac-filter-benef">Bénéficiaire</label>

            <button type="button" class="filter-reset d-none btn btn-sm btn-close-white"
                    data-target="#transac-filter-benef">
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>

        <div class="d-flex gap-1">
            <div class="form-floating">
                <input id="transac-filter-date-start" name="date_start" type="date" class="form-control"/>
                <label for="transac-filter-date-start">Début</label>
            </div>

            <div class="form-floating">
                <input id="transac-filter-date-end" name="date_end" type="date" class="form-control"/>
                <label for="transac-filter-date-end">Fin</label>
            </div>
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
            data-bs-target="#modal-transac-form"
            data-action="create"
        >
            <i class="fas fa-plus-circle"></i> Créer
        </button>
    </div>
</div>
