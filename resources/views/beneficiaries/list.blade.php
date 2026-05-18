@php use App\Models\Beneficiary; @endphp

<div>
    @if ($beneficiaries->lastPage() > 1)
        <div class="pagination-wrapper">
            {{ $beneficiaries->links() }}
        </div>
    @endif

    <div class="d-flex justify-content-end">
        <div id="bulk-action-wrapper" class="d-none">
            <button id="bulk-sync-all" type="button" class="btn btn-sm btn-primary" data-url="{{ route('benef_sync') }}">
                <i class="fas fa-link"></i>
            </button>

            <button
                id="bulk-add-categ-all"
                type="button"
                class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modal-benef-bulk-form"
                data-action="bulk-edit"
            >
                <i class="fas fa-pencil"></i>
            </button>
        </div>
    </div>

    <table class="mt-3 table table-striped table-bordered align-middle">
        <thead>
            <th style="width: 2rem;">
                <input id="bulk-select-all" type="checkbox" class="form-check" />
            </th>

            <th>Nom moche</th>
            <th>Nom joli</th>
            <th>Catégorie par défaut</th>
            <th>Description</th>
            <th style="width: 9rem;"></th>
        </thead>

        <tbody>
            @php /** @var Beneficiary $beneficiary */ @endphp
            @foreach ($beneficiaries as $beneficiary)
                <tr>
                    <td>
                        <input
                            type="checkbox"
                            class="bulk-select form-check"
                            data-benef_id="{{ $beneficiary->id }}"
                        />
                    </td>

                    <td>{{ $beneficiary->raw_name }}</td>
                    <td>{{ $beneficiary->pretty_name }}</td>
                    <td>{{ $beneficiary->c_appellation }}</td>
                    <td>{{ $beneficiary->description }}</td>

                    <td class="text-center">
                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-benef-form"
                            data-action="edit"
                            data-type="bénéficiaire"
                            data-url="{{ route('benef_get', $beneficiary->id) }}"
                        >
                            <i class="fas fa-pencil"></i>
                        </button>

                        @if (!empty($beneficiary->category_id))
                            <button
                                type="button"
                                class="ms-1 btn btn-sm btn-primary sync-categories"
                                data-url="{{ route('benef_sync') }}"
                                data-benef_id="{{ $beneficiary->id }}"
                            >
                                <i class="fas fa-link"></i>
                            </button>
                        @endif

                        <button
                            type="button"
                            class="ms-1 btn btn-sm btn-danger delete-benef"
                            data-url="{{ route('benef_delete', $beneficiary->id) }}"
                            data-raw_name="{{ $beneficiary->raw_name }}"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    @if ($beneficiaries->lastPage() > 1)
        <div class="pagination-wrapper">
            {{ $beneficiaries->links() }}
        </div>
    @endif
</div>
