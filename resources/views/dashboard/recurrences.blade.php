@php
    use App\Models\TransacRecurringPattern;
    use Carbon\Carbon;

    foreach ($active_recurrences as $recurrence) {

    }
@endphp

<div class="w-75 mx-auto content-wrapper">
    <div>
        Total : {{ formatAmount(abs($active_recurrences->pluck('amount')->sum())) }}€
    </div>

    <table class="mt-3 table table-striped table-bordered align-middle">
        <thead>
            <tr>
                <th>Label</th>
                <th style="width: 6rem;">Montant</th>
                <th style="width: 6rem;">Période</th>
                <th style="width: 6rem;">Date de fin</th>
                <th {{--style="width: 3rem;"--}}></th>
            </tr>
        </thead>

        <tbody>
            @php /** @var TransacRecurringPattern $recurrence */ @endphp
            @forelse($active_recurrences as $recurrence)
                <tr>
                    <td>{{ $recurrence->label }}</td>
                    <td>{{ formatAmount($recurrence->amount) }}€</td>

                    <td>{{ $recurrence->frequency_count }} {{ $recurrence->getUnitLabel() }}</td>

                    <td class="text-center">
                        @if (!empty($recurrence->ends_at))
                            {{ Carbon::parse($recurrence->ends_at)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>

                    <td>
                        @php
                            $related_expanses = $expanses->filter(function ($item) use ($recurrence) {
                                return !empty($item->recurring_pattern_id) && $item->recurring_pattern_id === $recurrence->id;
                            })->values();
                            dump($related_expanses->toArray());
                        @endphp
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
