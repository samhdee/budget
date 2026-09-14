@php
    use App\Enums\TransactionType;
    use App\Models\Category;
    use App\Models\Label;
    use Carbon\Carbon;
@endphp

<div class="d-flex justify-content-between align-items-center">
    <div
        id="transactions-filter-wrapper"
        class="filters-wrapper d-flex gap-3"
        data-url="{{ route('transac_filter') }}"
        data-target="#transac-list-wrapper"
    >
        <div class="form-floating">
            <select id="transac-filter-type" name="sign" class="form-select">
                <option value="">Tout</option>
                <option value="negative" selected>Débit</option>
                <option value="positive">Crédit</option>
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

            <label for="transac-filter-type">Catégorie</label>
        </div>

        <div class="form-floating">
            <select id="transac-filter-label" name="label_id" class="form-select">
                <option value="">Tous</option>

                @php /** @var Label $label */ @endphp
                @foreach($labels as $label)
                    <option value="{{ $label->id }}">
                        {{ $label->appellation }}
                    </option>
                @endforeach
            </select>

            <label for="transac-filter-type">Label</label>
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

            <button
                type="button"
                class="filter-reset d-none btn btn-sm btn-close-white"
                data-target="#transac-filter-benef"
            >
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>

        <div class="d-flex gap-1 form-floating">
            <input
                type="month"
                name="month"
                class="form-control"
                min="{{ Carbon::parse($first_date->occurred_at)->startOfMonth()->format('Y-m') }}"
                max="{{ Carbon::now()->format('Y-m') }}"
                value="{{ Carbon::now()->format('Y-m') }}"
            />

            <label for="transac-filter-month">Mois</label>
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
