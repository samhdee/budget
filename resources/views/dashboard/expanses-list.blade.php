@php
    use App\Enums\TransactionType;
    use App\Models\Transaction;
    use Carbon\Carbon;
    use Illuminate\Pagination\LengthAwarePaginator;
    /** @var LengthAwarePaginator $transactions */
@endphp

<div class="content-wrapper">
    <div class="pagination-wrapper">
        {{ $transac_expanses->links() }}
    </div>

    <div class="d-flex justify-content-end">
        <div class="d-none bulk-action-wrapper">
            <button id="bulk-delete" type="button" class="btn btn-sm btn-danger">
                <i class="fas fa-trash"></i>
            </button>
        </div>
    </div>

    <table class="table table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th style="width: 2rem;">
                    <input type="checkbox" class="form-check bulk-select-all" />
                </th>
                <th style="width: 6rem">Date</th>
                <th style="width: 7rem">Montant</th>
                <th style="width: 8rem;">Type</th>
                <th>Bénéficiaire</th>
                <th>Catégorie</th>
                <th style="width: 12rem">Labels</th>
                <th style="width: 2rem;">
                    <i class="fas fa-repeat"></i>
                </th>
                <th style="width: 6rem"></th>
            </tr>
        </thead>

        <tbody>
            @php /** @var Transaction $transaction */ @endphp
            @forelse ($transac_expanses as $transaction)
                <tr>
                    <td>
                        <input type="checkbox" class="form-check bulk-select" data-item_id="{{ $transaction->id }}"/>
                    </td>

                    <td>{{ Carbon::createFromFormat('Y-m-d', $transaction->occurred_at)->format('d/m/Y') }}</td>
                    <td class="text-{{ $transaction->amount < 0 ? 'danger' : 'success' }}">{{ $transaction->amount }}€</td>
                    <td>{{ getTransactionTypeLabel($transaction->type) }}</td>

                    <td>
                        @if (!empty($transaction->beneficiary_id))
                            <a
                                href="javascript:void(0)"
                                data-bs-toggle="modal"
                                data-bs-target="#modal-benef-form"
                                data-item_id="{{ $transaction->beneficiary_id }}"
                                data-url="{{ route('benef_get', $transaction->beneficiary_id) }}"
                                data-action="edit"
                                data-type="bénéficiaire"
                                data-list="#expanses-list-wrapper"
                                title="{{ $transaction->raw_name }}"
                            >
                                {{ !empty($transaction->pretty_name) ? $transaction->pretty_name : $transaction->raw_name }}
                            </a>
                        @endif
                    </td>

                    <td>{{ $transaction->c_appellation }}</td>

                    <td>
                        @foreach($transaction->labels as $label)
                            <span class="badge text-bg-info">{{ $label->appellation }}</span>
                        @endforeach
                    </td>

                    <td class="text-center">
                        @if (!empty($transaction->recurringPattern && !empty($transaction->recurringPattern->active)))
                            <i class="fas fa-repeat"></i>
                        @endif
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
                            data-url="{{ route('transac_delete', $transaction->id) }}"
                            data-message="Supprimer cette transaction ?"
                        >
                            <i class="fas fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center text-muted fst-italic">
                        <i class="fa-solid fa-ban me-1"></i> Aucun résultat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
