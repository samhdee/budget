@php
    use App\Models\TransacRecurringPattern;
    use Carbon\Carbon;
@endphp

<div class="content-wrapper">
    <div class="d-flex justify-content-end">
        <div class="d-none bulk-action-wrapper">
            <button
                type="button"
                class="btn btn-sm btn-primary"
                data-bs-toggle="modal"
                data-bs-target="#modal-recur-bulk-toggle-active-form"
                data-action="bulk"
            >
                <i class="fas fa-pencil"></i>
            </button>

            <button
                type="button"
                class="btn btn-sm btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modal-recur-bulk-toggle-active-form"
                data-action="bulk"
            >
                <i class="fas fa-rotate-left"></i>
            </button>
        </div>
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
                        <a
                            href="javascript:void(0)"
                            data-bs-toggle="modal"
                            data-bs-target="#modal-benef-form"
                            data-item_id="{{ $inactive_recurrence->beneficiary_id }}"
                            data-url="{{ route('benef_get', $inactive_recurrence->beneficiary_id) }}"
                            data-action="edit"
                            data-type="bénéficiaire"
                            title="{{ $inactive_recurrence->raw_name }}"
                        >
                            {{ !empty($inactive_recurrence->pretty_name) ? $inactive_recurrence->pretty_name : $inactive_recurrence->raw_name }}
                        </a>
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
                            <i class="fas fa-rotate-left"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-muted text-center fst-italic">
                        <i class="me-1 fas fa-ban"></i> Aucun résultat
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
