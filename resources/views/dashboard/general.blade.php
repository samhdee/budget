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
