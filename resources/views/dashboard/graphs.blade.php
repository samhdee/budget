@php
    use App\Models\Label;
    use App\Models\TransacRecurringPattern;
    use App\Models\Transaction;

    // Graph : dépenses vs. revenus
    $expanses = Transaction::getList(
        ['sign' => 'negative', 'date_start' => $filter_date_start, 'date_end' => $filter_date_end], false
    );

    $revenus = Transaction::getList(
        ['sign' => 'positive', 'date_start' => $filter_date_start, 'date_end' => $filter_date_end], false
    );

    if ($expanses->isNotEmpty() || $revenus->isNotEmpty()) {
        $values_exp_vs_rev = [
            'labels' => ['Dépenses', 'Revenus'],
            'values' => [
                abs($expanses->pluck('amount')->sum()),
                $revenus->pluck('amount')->sum()
            ],
        ];
    }

    // Graph : projection fin de mois
    // @TODO: Prendre la date du filtre comme référence (check en fonction de created_at ?)
    $last_month_recurrences = TransacRecurringPattern::getActiveMonthlyRecurrences();
    $recurrences_sum = 0;

    /** @var TransacRecurringPattern $recurrence */
    foreach ($last_month_recurrences as $recurrence) {
        $expanse = $expanses->filter(function ($item) use ($recurrence) {
            return !empty($item->recurring_pattern_id) && $item->recurring_pattern_id === $recurrence->id;
        })->values();

        $tmp_sum = $recurrence->frequency_count === 1 && $recurrence->frequency_unit === 'month'
            ? $recurrence->amount
            : $recurrence->amount * 4 / $recurrence->frequency_count;

        if ($expanse->isNotEmpty()) {
            $tmp_sum -= $expanse->pluck('amount')->sum();
        }

        $recurrences_sum += $tmp_sum;
    }

    if (!empty($recurrences_sum) && $expanses->isNotEmpty() || $revenus->isNotEmpty()) {
        $values_projection = [
            'labels' => ['Dépenses', 'Revenus'],
            'values' => [
                abs($recurrences_sum) + abs($expanses->pluck('amount')->sum()),
                $revenus->pluck('amount')->sum()
            ],
        ];
    }

    // Graph : dépenses par catégorie
    $expanses_with_categ = $expanses->filter(function ($item) {
        return !empty($item->category);
    })
        ->values()
        ->groupBy('category_id');

    /** @var Transaction $expanse */
    foreach ($expanses_with_categ as $expanse) {
        $values_exp_by_categ['labels'][] = $expanse->first()->category->appellation;
        $values_exp_by_categ['values'][] = abs($expanse->pluck('amount')->sum());
    }

    // Graph : dépenses par label
    $expanses_with_labels = $expanses->filter(function ($item) {
        return $item->labels->isNotEmpty();
    })->values();

    /** @var Transaction $expanse */
    foreach ($expanses_with_labels as $expanse) {
        /** @var Label $label */
        foreach ($expanse->labels as $label) {
            if (empty($values_exp_by_label['values'][$label->appellation])) {
                $values_exp_by_label['values'][$label->appellation] = abs($expanse->amount);
                $values_exp_by_label['labels'][$label->appellation] = $label->appellation;
            } else {
                $values_exp_by_label['values'][$label->appellation] += abs($expanse->amount);
            }
        }
    }

    $values_exp_by_label['values'] = array_values($values_exp_by_label['values']);
    $values_exp_by_label['labels'] = array_values($values_exp_by_label['labels']);
@endphp

<div class="row justify-content-between">
    @if (!empty($values_exp_vs_rev))
        <div class="col-5">
            <canvas
                id="dashboard-rev-exp-chart"
                data-values='@json($values_exp_vs_rev)'
                data-unit="€"
                data-title="Dépenses vs. Revenus"
            ></canvas>
        </div>
    @endif

    @if (!empty($values_projection))
        <div class="col-5">
            <canvas
                id="dashboard-projection-chart"
                data-values='@json($values_projection)'
                data-unit="€"
                data-title="Projection fin de mois (*)"
            ></canvas>

            <p class="text-muted fst-italic text-small">
                (*) Ne compte pas l&rsquo;alimentation
            </p>
        </div>
    @endif
</div>

<hr class="my-5"/>

<div class="row justify-content-between">
    @if (!empty($values_exp_by_categ))
        <div class="col-5">
            <h3 class="text-center">Dépenses par catégories</h3>

            <canvas
                id="dashboard-exp-by-categ-chart"
                class="mt-3"
                data-values='@json($values_exp_by_categ)'
            ></canvas>
        </div>
    @endif

    @if (!empty($values_exp_by_label))
        <div class="col-5">
            <h3 class="text-center">Dépenses par labels</h3>

            <canvas
                id="dashboard-exp-by-label-chart"
                class="mt-3"
                data-values='@json($values_exp_by_label)'
            ></canvas>
        </div>
    @endif
</div>
