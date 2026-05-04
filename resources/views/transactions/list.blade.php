@php
    use App\Enums\TransactionType;
    use Carbon\Carbon;
@endphp

<table class=" mx-auto w-75 table table-striped table-bordered align-middle">
    <thead>
        <tr>
            <th style="width: 7rem">Date</th>
            <th style="width: 7rem">Montant</th>
            <th>Type</th>
            <th>Bénéficiaire</th>
            <th style="width: 12rem">Notes</th>
            <th style="width: 4rem"></th>
        </tr>
    </thead>

    <tbody>
        @forelse ($transactions as $transaction)
            <tr>
                <td>{{ Carbon::createFromFormat('Y-m-d', $transaction->occurred_at)->format('d/m/Y') }}</td>
                <td class="text-{{ $transaction->amount < 0 ? 'danger' : 'success' }}">{{ $transaction->amount }}€</td>
                <td>{{ getTransactionTypeLabel($transaction->type) }}</td>

                <td>
                    <a id="transac-list-benef-edit" href="#" data-bs-toggle="modal" data-bs-target="#modal-benef-form">
                        {{ !empty($transaction->pretty_name) ? $transaction->pretty_name : $transaction->raw_name }}
                    </a>
                </td>

                <td>{{ $transaction->notes }}</td>

                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modal-transac-form"
                        data-transac-id="{{ $transaction->id }}"
                        data-action="edit"
                    >
                        <i class="fas fa-pencil"></i>
                    </button>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted fst-italic">
                    <i class="fa-solid fa-ban me-1"></i> Aucun résultat
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
