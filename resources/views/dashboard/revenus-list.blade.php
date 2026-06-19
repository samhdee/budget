@php
    use App\Enums\TransactionType;
    use App\Models\Transaction;
    use Carbon\Carbon;
@endphp

<div class="content-wrapper">
    <div class="mt-4 d-flex justify-content-end">
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

    <div class="mt-2 d-flex justify-content-end">
        <div class="d-none bulk-action-wrapper">
            <button
                type="button"
                class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modal-transac-bulk-form"
                data-action="bulk"
            >
                <i class="fas fa-pencil"></i>
            </button>

            <button
                type="button"
                class="ms-1 btn btn-sm btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#modal-transac-bulk-delete-form"
            >
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    <table class="mt-2 table table-striped table-bordered align-middle">
        <thead>
        <tr>
            <th style="width: 2rem;">
                <input type="checkbox" class="form-check bulk-select-all" />
            </th>
            <th style="width: 7rem">Date</th>
            <th style="width: 7rem">Montant</th>
            <th>Type</th>
            <th>Bénéficiaire</th>
            <th>Catégorie</th>
            <th style="width: 12rem">Labels</th>
            <th style="width: 6rem"></th>
        </tr>
        </thead>

        <tbody>
        @php /** @var Transaction $transaction */ @endphp
        @forelse ($transac_revenus as $transaction)
            <tr>
                <td>
                    <input type="checkbox" class="form-check bulk-select" data-item_id="{{ $transaction->id }}"/>
                </td>

                <td>{{ Carbon::createFromFormat('Y-m-d', $transaction->occurred_at)->format('d/m/Y') }}</td>

                <td class="text-success">
                    {{ formatAmount($transaction->amount) }}€
                </td>

                <td>{{ getTransactionTypeLabel($transaction->type) }}</td>

                <td>
                    @if (!empty($transaction->beneficiary))
                        <a
                            href="javascript:void(0)"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-benef-form"
                            data-item_id="{{ $transaction->beneficiary->id }}"
                            data-url="{{ route('benef_get', $transaction->beneficiary->id) }}"
                            data-action="edit"
                            data-type="bénéficiaire"
                            data-list="#expanses-list-wrapper"
                            title="{{ $transaction->beneficiary->raw_name }}"
                        >
                            {{
                                !empty($transaction->beneficiary->pretty_name)
                                    ? $transaction->beneficiary->pretty_name
                                    : $transaction->beneficiary->raw_name
                            }}
                        </a>
                    @endif
                </td>

                <td>
                    @if (!empty($transaction->category))
                        {{ $transaction->category->appellation }}
                    @endif
                </td>

                <td>
                    @foreach($transaction->labels as $label)
                        <span class="badge text-bg-info">{{ $label->appellation }}</span>
                    @endforeach
                </td>

                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-transac-form"
                        data-url="{{ route('transac_get', $transaction->id) }}"
                        data-type="transaction"
                        data-action="edit"
                    >
                        <i class="fas fa-pencil"></i>
                    </button>

                    <button
                        type="button"
                        class="ms-1 btn btn-sm btn-danger btn-action confirm-before-action"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-transac-delete"
                        data-url="{{ route('transac_get', $transaction->id) }}"
                        data-message="Supprimer cette transaction ?"
                    >
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="text-center text-muted fst-italic">
                    <i class="fa-solid fa-ban me-1"></i> Aucun résultat
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
