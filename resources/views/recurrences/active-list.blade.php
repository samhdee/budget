@php
    use App\Models\TransacRecurringPattern;
    use Carbon\Carbon;
@endphp

<div class="content-wrapper">
    <div class="d-flex justify-content-end">
        <div class="d-none bulk-action-wrapper">
            <button
                id="bulk-delete"
                type="button"
                class="btn btn-sm btn-danger"
                data-bs-toggle="modal"
                data-bs-target="#modal-recur-bulk-toggle-active-form"
            >
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>
    </div>

    <div>
        Total : {{ formatAmount(abs($recurrences->pluck('amount')->sum())) }}€
    </div>

    <table class="mt-3 table table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th style="width: 2rem;">
                    <input class="form-check bulk-select-all" type="checkbox" />
                </th>

                <th>Label</th>
                <th style="width: 6rem;">Montant</th>
                <th>Bénéficiaire</th>
                <th style="width: 6rem;">Période</th>
                <th style="width: 6rem">Transactions</th>
                <th style="width: 6rem;">Date de fin</th>
                <th style="width: 8.7rem;"></th>
            </tr>
        </thead>

        <tbody>
            @php /** @var TransacRecurringPattern $recurrence */ @endphp
            @forelse($recurrences as $recurrence)
                <tr>
                    <td>
                        <input class="bulk-select form-check" type="checkbox" data-item_id="{{ $recurrence->id }}"/>
                    </td>

                    <td>{{ $recurrence->label }}</td>
                    <td>{{ formatAmount($recurrence->amount) }}€</td>

                    <td>
                        <a
                            href="javascript:void(0)"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-benef-form"
                            data-item_id="{{ $recurrence->beneficiary_id }}"
                            data-url="{{ route('benef_get', $recurrence->beneficiary_id) }}"
                            data-action="edit"
                            data-type="bénéficiaire"
                            title="{{ $recurrence->raw_name }}"
                        >
                            {{ !empty($recurrence->pretty_name) ? $recurrence->pretty_name : $recurrence->raw_name }}
                        </a>
                    </td>

                    <td>{{ $recurrence->frequency_count }} {{ $recurrence->getUnitLabel() }}</td>
                    <td class="text-center">{{ $recurrence->nb_transactions }}</td>

                    <td class="text-center">
                        @if (!empty($recurrence->ends_at))
                            {{ Carbon::parse($recurrence->ends_at)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        <button
                            type="button"
                            class="btn btn-sm btn-primary"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-recurrence-form"
                            data-url="{{ route('recurrences_get', $recurrence->id) }}"
                        >
                            <i class="fas fa-pencil"></i>
                        </button>

                        <button
                            type="button"
                            class="ms-1 btn btn-sm btn-success"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-recurrence-transacs-form"
                            data-url="{{ route('recurrences_get_transacs', $recurrence->id) }}"
                            data-item_id="{{ $recurrence->id }}"
                        >
                            <i class="fas fa-plus-circle"></i>
                        </button>

                        <button
                            type="button"
                            class="ms-1 btn btn-sm btn-danger btn-action confirm-before-action"
                            data-url="{{ route('recurrences_toggle_active', $recurrence->id) }}"
                            data-message="Désactiver cette récurrence ?"
                            data-list="#recurrences-list-wrapper"
                        >
                            <i class="fas fa-xmark-circle"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-muted text-center fst-italic">
                        <i class="me-1 fas fa-ban"></i> Aucun résultat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
