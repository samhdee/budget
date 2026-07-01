@php
    use App\Models\Label;
    use App\Models\TransacRecurringPattern;
    use App\Models\Transaction;

    // Graph : dépenses vs. revenus
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
        $related_expanses = $expanses->filter(function ($item) use ($recurrence) {
            return !empty($item->recurring_pattern_id) && $item->recurring_pattern_id === $recurrence->id;
        })->values();

        $tmp_sum = $recurrence->frequency_count === 1 && $recurrence->frequency_unit === 'month'
            ? $recurrence->amount
            : $recurrence->amount * 4 / $recurrence->frequency_count;

        if ($related_expanses->isNotEmpty()) {
            $tmp_sum -= $related_expanses->pluck('amount')->sum();
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
                (*) Projection approximative
            </p>
        </div>
    @endif
</div>

<hr class="my-5"/>

@php
    // Graph : dépenses par catégorie
    $expanses_charges = $expanses->filter(function ($item) {
        return $item->labels->isNotEmpty() && !empty($item->category) && $item->category->appellation === 'Charges';
    })
        ->values();

    /** @var Transaction $related_expanses */
    foreach ($expanses_charges as $related_expanses) {
        /** @var Label $label */
        foreach ($related_expanses->labels as $label) {
            if (empty($values_exp_charges['values'][$label->appellation])) {
                $values_exp_charges['values'][$label->appellation] = abs($related_expanses->amount);
                $values_exp_charges['labels'][$label->appellation] = $label->appellation;
            } else {
                $values_exp_charges['values'][$label->appellation] += abs($related_expanses->amount);
            }
        }
    }

    if (!empty($values_exp_charges)) {
        ksort($values_exp_charges['values']);
        ksort($values_exp_charges['labels']);
        $values_exp_charges['values'] = array_values($values_exp_charges['values']);
        $values_exp_charges['labels'] = array_values($values_exp_charges['labels']);
    }

    // Graph : dépenses par label
    $expanses_loisirs = $expanses->filter(function ($item) {
        return $item->labels->isNotEmpty() && !empty($item->category) && $item->category->appellation === 'Loisirs';
    })->values();

    /** @var Transaction $related_expanses */
    foreach ($expanses_loisirs as $related_expanses) {
        /** @var Label $label */
        foreach ($related_expanses->labels as $label) {
            if (empty($values_exp_loisirs['values'][$label->appellation])) {
                $values_exp_loisirs['values'][$label->appellation] = abs($related_expanses->amount);
                $values_exp_loisirs['labels'][$label->appellation] = $label->appellation;
            } else {
                $values_exp_loisirs['values'][$label->appellation] += abs($related_expanses->amount);
            }
        }
    }

    if (!empty($values_exp_loisirs)) {
        ksort($values_exp_loisirs['values']);
        ksort($values_exp_loisirs['labels']);
        $values_exp_loisirs['values'] = array_values($values_exp_loisirs['values']);
        $values_exp_loisirs['labels'] = array_values($values_exp_loisirs['labels']);
    }
@endphp

<div class="row justify-content-between">
    @if (!empty($values_exp_charges))
        <div class="col-5">
            <h3 class="text-center">Charges - Dépenses par labels</h3>

            <canvas
                id="dashboard-exp-charges-chart"
                class="mt-3"
                data-values='@json($values_exp_charges)'
            ></canvas>
        </div>
    @endif

    @if (!empty($values_exp_loisirs))
        <div class="col-5">
            <h3 class="text-center">Loisirs - Dépenses par labels</h3>

            <canvas
                id="dashboard-exp-loisirs-chart"
                class="mt-3"
                data-values='@json($values_exp_loisirs)'
            ></canvas>
        </div>
    @endif
</div>

<hr class="my-5"/>

@php
    // Graph : dépenses par catégorie
    $expanses_with_categ = $expanses->filter(function ($item) {
        return !empty($item->category);
    })
        ->values()
        ->groupBy('category_id');

    /** @var Transaction $related_expanses */
    foreach ($expanses_with_categ as $related_expanses) {
        $values_exp_by_categ['labels'][] = $related_expanses->first()->category->appellation;
        $values_exp_by_categ['values'][] = abs($related_expanses->pluck('amount')->sum());
    }

    // Graph : dépenses par label
    $expanses_with_labels = $expanses->filter(function ($item) {
        return $item->labels->isNotEmpty();
    })->values();

    /** @var Transaction $related_expanses */
    foreach ($expanses_with_labels as $related_expanses) {
        /** @var Label $label */
        foreach ($related_expanses->labels as $label) {
            if (empty($values_exp_by_label['values'][$label->appellation])) {
                $values_exp_by_label['values'][$label->appellation] = abs($related_expanses->amount);
                $values_exp_by_label['labels'][$label->appellation] = $label->appellation;
            } else {
                $values_exp_by_label['values'][$label->appellation] += abs($related_expanses->amount);
            }
        }
    }

    if (!empty($values_exp_by_label)) {
        $values_exp_by_label['values'] = array_values($values_exp_by_label['values']);
        $values_exp_by_label['labels'] = array_values($values_exp_by_label['labels']);
    }
@endphp

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
