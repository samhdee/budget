@php
    use App\Enums\TransactionType;
    use Carbon\Carbon;
@endphp

<table class=" mx-auto w-75 table table-striped table-bordered align-middle">
    <thead>
        <tr>
            <th>Date</th>
            <th style="width: 12rem">Montant</th>
            <th>Type</th>
            <th>Bénéficiaire</th>
            <th style="width: 4rem"></th>
        </tr>
    </thead>

    <tbody>
        @forelse ($transactions as $transaction)
            <tr>
                <td>{{ Carbon::createFromFormat('Y-m-d', $transaction->occurred_at)->format('d/m/Y') }}</td>
                <td class="text-{{ $transaction->amount < 0 ? 'danger' : 'success' }}">{{ $transaction->amount }}€</td>

                <td>
                    @switch ($transaction->type)
                        @case (TransactionType::card->name)
                            CB
                            @break
                        @case (TransactionType::collection->name)
                            Prélèvement
                            @break
                        @case (TransactionType::wero->name)
                            Wero
                            @break
                        @case (TransactionType::transfer->name)
                            Virement
                            @break
                        @case (TransactionType::perma_transfer->name)
                            Virement permanent
                            @break
                        @case (TransactionType::withdrawal->name)
                            Retrait
                            @break
                        @default
                            Autre
                   @endswitch
                </td>

                <td>{{ !empty($transaction->pretty_name) ? $transaction->pretty_name : $transaction->raw_name }}</td>

                <td class="text-center">
                    <button
                        type="button"
                        class="btn btn-sm btn-primary"
                        data-bs-toggle="modal"
                        data-bs-target="#modal_transac_form"
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
