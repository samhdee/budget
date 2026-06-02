@php
    use App\Enums\TransactionType;
    use App\Models\Category;
@endphp

<div class="d-flex justify-content-between align-items-center">
    <div
        id="expanses-filter-wrapper"
        class="filters-wrapper d-flex gap-3"
        data-url="{{ route('dashboard_exp_filter') }}"
        data-target="#expanses-list-wrapper"
    >
        <div class="form-floating">
            <select id="expanses-filter-type" name="type" class="form-select">
                <option value="">Tous</option>

                @foreach(TransactionType::cases() as $transac_type)
                    <option value="{{ $transac_type->name }}">
                        {{ getTransactionTypeLabel($transac_type->name) }}
                    </option>
                @endforeach
            </select>

            <label for="expanses-filter-type">Type</label>
        </div>

        <div class="form-floating">
            <select id="expanses-filter-category" name="category_id" class="form-select">
                <option value="">Tous</option>

                @php /** @var Category $category */ @endphp
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">
                        {{ $category->appellation }}
                    </option>
                @endforeach
            </select>

            <label for="expanses-filter-type">Catégorie</label>
        </div>

        <div class="filter-wrapper with-reset form-floating">
            <input
                id="expanses-filter-benef"
                type="text"
                name="benef_name"
                class="form-control"
                size="30"
            />

            <label for="expanses-filter-benef">Bénéficiaire</label>

            <button
                type="button"
                class="filter-reset d-none btn btn-sm btn-close-white"
                data-target="#expanses-filter-benef"
            >
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>

        <div class="d-none">
            <input id="dash-filter-date-start" type="date" name="date_start" value="{{ $filter_date_start }}"/>
            <input id="dash-filter-date-end" type="date" name="date_end" value="{{ $filter_date_end }}"/>
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
