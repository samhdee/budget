@php use App\Models\Beneficiary; @endphp

<div>
    <div class="pagination-wrapper">
        {{ $beneficiaries->links() }}
    </div>

    <div class="d-flex justify-content-end">
        <div id="bulk-action-wrapper" class="d-none">
            <button
                id="bulk-sync-all"
                type="button"
                class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modal-benef-bulk-sync-form"
                data-action="bulk"
            >
                <i class="fas fa-link"></i>
            </button>

            <button
                type="button"
                class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modal-benef-bulk-form"
                data-action="bulk"
            >
                <i class="fas fa-pencil"></i>
            </button>
        </div>
    </div>

    <table class="mt-2 table table-striped table-bordered align-middle">
        <thead>
            <th style="width: 2rem;">
                <input id="bulk-select-all" type="checkbox" class="form-check" />
            </th>

            <th>Nom moche</th>
            <th>Nom joli</th>
            <th>Catégorie par défaut</th>
            <th style="width: 6.5rem;">Transactions</th>
            <th>Description</th>
            <th style="width: 6rem;"></th>
        </thead>

        <tbody>
            @php /** @var Beneficiary $beneficiary */ @endphp
            @forelse ($beneficiaries as $beneficiary)
                <tr>
                    <td>
                        <input type="checkbox" class="bulk-select form-check" data-item_id="{{ $beneficiary->id }}"/>
                    </td>

                    <td>{{ $beneficiary->raw_name }}</td>
                    <td>{{ $beneficiary->pretty_name }}</td>
                    <td>{{ $beneficiary->c_appellation }}</td>
                    <td class="text-center">{{ $beneficiary->nb_transactions }}</td>
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
                                class="ms-1 btn btn-sm btn-primary confirm-before-action"
                                data-url="{{ route('benef_sync') }}"
                                data-benef_id="{{ $beneficiary->id }}"
                            >
                                <i class="fas fa-link"></i>
                            </button>
                        @endif

                        @if (empty($beneficiary->nb_transactions))
                            <button
                                type="button"
                                class="ms-1 btn btn-sm btn-danger btn-action confirm-before-action"
                                data-url="{{ route('benef_delete', $beneficiary->id) }}"
                                data-message="Supprimer ce bénéficiaire ?"
                            >
                                <i class="fas fa-trash"></i>
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-muted">
                        <i class="me-1 fas fa-ban"></i> Aucun résultat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
