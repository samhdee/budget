@php
    use App\Models\TransacRecurringPattern;
    use Carbon\Carbon;
@endphp

<table class="table table-striped table-bordered align-middle">
    <thead>
        <tr>
            <th style="width: 2rem;">
                <input id="bulk-select-all" class="form-check" type="checkbox"/>
            </th>

            <th>Label</th>
            <th style="width: 6rem;">Montant</th>
            <th>Bénéficiaire</th>
            <th style="width: 6rem;">Période</th>
            <th style="width: 7rem;">Date de fin</th>
            <th style="width: 6rem;"></th>
        </tr>
    </thead>

    <tbody>
        @php /** @var TransacRecurringPattern $recurrence */ @endphp
        @forelse($recurrences as $recurrence)
            <tr>
                <td>
                    <input class="bulk-select form-check" type="checkbox" data-recurrence_id="{{ $recurrence->id }}"/>
                </td>

                <td>{{ $recurrence->label }}</td>
                <td>{{ $recurrence->amount }}€</td>

                <td>
                    @if(!empty($recurrence->pretty_name))
                        <span title="{{ $recurrence->raw_name }}">{{ $recurrence->pretty_name }}</span>
                    @else
                        {{ $recurrence->raw_name }}
                    @endif
                </td>

                <td>
                    {{ $recurrence->frequency_count }} {{ $recurrence->getUnitLabel() }}
                </td>

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
                        class="ms-1 btn btn-sm btn-danger btn-action confirm-before-action"
                        data-url="{{ route('recurrences_deactivate', $recurrence->id) }}"
                        data-message="Désactiver cette récurrence ?"
                    >
                        <i class="fas fa-trash"></i>
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
