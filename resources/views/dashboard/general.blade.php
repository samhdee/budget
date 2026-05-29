<div class="row justify-content-between">
    <div class="col-5">
        <canvas
            id="dashboard-rev-exp-chart"
            data-values='@json($values_exp_vs_rev)'
            data-unit="€"
            data-title="Dépenses vs. Revenus"
        ></canvas>
    </div>

    <div class="col-5">
        <canvas
            id="dashboard-exp-by-categ-chart"
            data-values='@json($values_exp_by_categ)'
            data-unit="€"
            data-title="Dépenses par catégories"
        ></canvas>
    </div>
</div>
