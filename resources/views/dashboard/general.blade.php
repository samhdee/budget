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
                (*) Ne compte pas les récurrences prenant fin ce mois-ci ni l&rsquo;alimentation
            </p>
        </div>
    @endif
</div>

<hr class="my-5" />

<div class="row justify-content-between">
    @if (!empty($values_exp_by_categ))
        <div class="col-5">
            <canvas
                id="dashboard-exp-by-categ-chart"
                data-values='@json($values_exp_by_categ)'
                data-title="Dépenses par catégories"
            ></canvas>
        </div>
    @endif

    @if (!empty($values_exp_by_label))
        <div class="col-5">
            <canvas
                id="dashboard-exp-by-label-chart"
                data-values='@json($values_exp_by_label)'
                data-title="Dépenses par labels"
            ></canvas>
        </div>
    @endif
</div>
