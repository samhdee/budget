<div>
    @php
        $expanses_by_labels = [];

        foreach ($labels as $label) {
            $expanses_label = $expanses->filter(function ($transaction) use ($label) {
                return $transaction->labels->contains('id', $label->id);
            })->values();

            if ($expanses_label->isEmpty()) {
                continue;
            }

            $last_month_exp_label = $previous_month_expanses->filter(function ($transaction) use ($label) {
                return $transaction->labels->contains('id', $label->id);
            })->values();

            $expanses_by_labels[$label->appellation] = [
                'labels' => ['Mois courant', 'Mois précédent'],
                'values' => [
                    abs($expanses_label->sum('amount')),
                    abs($last_month_exp_label->sum('amount')),
                ]
            ];
        }
    @endphp

    <div class="d-flex justify-content-between flex-wrap">
        @foreach ($expanses_by_labels as $appellation => $expanse_by_label)
            <div class="mt-3 col-4">
                <h3 class="text-center">{{ $appellation }}</h3>

                <canvas
                    id="dashboard-exp-label-{{ $appellation }}"
                    class="mt-3 dashboard-exp-by-label"
                    data-values='@json($expanse_by_label)'
                    data-unit="€"
                    data-title={{ $appellation }}
                ></canvas>
            </div>
        @endforeach
    </div>
</div>
