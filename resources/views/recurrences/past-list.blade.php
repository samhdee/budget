@php
    use App\Models\TransacRecurringPattern;
    use Carbon\Carbon;
@endphp

<div class="content-wrapper">
    <div class="d-flex justify-content-end">
        <div class="d-none bulk-action-wrapper">
            <button id="bulk-delete" type="button" class="btn btn-sm btn-danger">
                <i class="fas fa-xmark-circle"></i>
            </button>
        </div>
    </div>

    <table class="mt-3 table table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th style="width: 2rem;">
                    <input class="form-check bulk-select-all" type="checkbox"/>
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
            @php /** @var TransacRecurringPattern $past_recurrence */ @endphp
            @forelse ($past_recurrences as $past_recurrence)
                <tr>
                    <td>
                        <input class="bulk-select form-check" type="checkbox" data-item_id="{{ $past_recurrence->id }}" />
                    </td>

                    <td>{{ $past_recurrence->label }}</td>
                    <td>{{ $past_recurrence->amount }}€</td>

                    <td>
                        @if(!empty($past_recurrence->pretty_name))
                            <span title="{{ $past_recurrence->raw_name }}">{{ $past_recurrence->pretty_name }}</span>
                        @else
                            {{ $past_recurrence->raw_name }}
                        @endif
                    </td>

                    <td>
                        {{ $past_recurrence->frequency_count }} {{ $past_recurrence->getUnitLabel() }}
                    </td>

                    <td class="text-center">
                        @if (!empty($past_recurrence->ends_at))
                            {{ Carbon::parse($past_recurrence->ends_at)->format('d/m/Y') }}
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
                            data-url="{{ route('recurrences_get', $past_recurrence->id) }}"
                        >
                            <i class="fas fa-pencil"></i>
                        </button>

                        <button
                            type="button"
                            class="ms-1 btn btn-sm btn-danger btn-action confirm-before-action"
                            data-url="{{ route('recurrences_toggle_active', $past_recurrence->id) }}"
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
