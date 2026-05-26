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
        <th style="width: 6rem;"></th>
    </tr>
    </thead>

    <tbody>
    @php /** @var TransacRecurringPattern $inactive_recurrence */ @endphp
    @forelse($inactive_recurrences as $inactive_recurrence)
        <tr>
            <td>
                <input class="bulk-select form-check" type="checkbox" data-item_id="{{ $inactive_recurrence->id }}"/>
            </td>

            <td>{{ $inactive_recurrence->label }}</td>
            <td>{{ $inactive_recurrence->amount }}€</td>

            <td>
                @if(!empty($inactive_recurrence->pretty_name))
                    <span title="{{ $inactive_recurrence->raw_name }}">{{ $inactive_recurrence->pretty_name }}</span>
                @else
                    {{ $inactive_recurrence->raw_name }}
                @endif
            </td>

            <td>
                {{ $inactive_recurrence->frequency_count }} {{ $inactive_recurrence->getUnitLabel() }}
            </td>

            <td>
                <button
                    type="button"
                    class="btn btn-sm btn-primary"
                    data-bs-toggle="modal"
                    data-bs-target="#modal-recurrence-form"
                    data-url="{{ route('recurrences_get', $inactive_recurrence->id) }}"
                >
                    <i class="fas fa-pencil"></i>
                </button>

                <button
                    type="button"
                    class="ms-1 btn btn-sm btn-success btn-action confirm-before-action"
                    data-url="{{ route('recurrences_toggle_active', $inactive_recurrence->id) }}"
                    data-message="Rétablir cette récurrence ?"
                    data-list="#recurrences-list-wrapper"
                    title="Rétablir"
                >
                    <i class="fas fa-arrows-rotate"></i>
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
