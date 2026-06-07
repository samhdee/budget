import Chart from 'chart.js/auto';

import './helpers/filters.js';
import './helpers/forms.js';

const init_rev_exp_chart = () => {
    const canvas_rev_exp = document.getElementById('dashboard-rev-exp-chart');
    const rev_exp_dataset = JSON.parse(canvas_rev_exp.dataset.values);

    new Chart(canvas_rev_exp, {
        type: 'bar',
        data: {
            labels: rev_exp_dataset.labels,
            datasets: [{
                axis: 'x',
                label: canvas_rev_exp.dataset.title,
                data: rev_exp_dataset.values,
                fill: false,
                backgroundColor: [
                    'rgb(255 99 99)',
                    'rgb(75 192 91)',
                ],
            }],
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return `${value}${canvas_rev_exp.dataset.unit}`;
                        }
                    },
                }
            }
        }
    });
};

const init_projection_chart = () => {
    const canvas_projection = document.getElementById('dashboard-projection-chart');
    const projection_dataset = JSON.parse(canvas_projection.dataset.values);

    new Chart(canvas_projection, {
        type: 'bar',
        data: {
            labels: projection_dataset.labels,
            datasets: [{
                axis: 'x',
                label: canvas_projection.dataset.title,
                data: projection_dataset.values,
                fill: false,
                backgroundColor: [
                    'rgb(255 99 99)',
                    'rgb(75 192 91)',
                ],
            }],
        },
        options: {
            indexAxis: 'y',
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        callback: function (value) {
                            return `${value}${canvas_projection.dataset.unit}`;
                        }
                    },
                }
            }
        }
    });
};

const init_exp_by_categ_chart = () => {
    const canvas_exp_by_categ = document.getElementById('dashboard-exp-by-categ-chart');
    const exp_by_categ_dataset = JSON.parse(canvas_exp_by_categ.dataset.values);

    new Chart(canvas_exp_by_categ, {
        type: 'pie',
        data: {
            labels: exp_by_categ_dataset.labels,
            datasets: [{
                axis: 'x',
                label: canvas_exp_by_categ.dataset.title,
                data: exp_by_categ_dataset.values,
            }],
        },
    });
};

const reload_dashboard = async () => {
    return await $.post(
        'dashboard/filter',
        {filters: {
            date_start: $('#transac-date-select').val(),
            active_tab: $('#transactions-tabs .nav-link.active').prop('id'),
        }},
        response => {
            $('#transac-global-wrapper').html(response);
            if ($('#dashboard-rev-exp-chart').length > 0) {
                init_rev_exp_chart();
            }

            if ($('#dashboard-projection-chart').length > 0) {
                init_projection_chart();
            }

            if ($('#dashboard-exp-by-categ-chart').length > 0) {
                init_exp_by_categ_chart();
            }
        }
    ).promise();
}

$(function () {
    if ($('#dashboard-rev-exp-chart').length > 0) {
        init_rev_exp_chart();
    }

    if ($('#dashboard-projection-chart').length > 0) {
        init_projection_chart();
    }

    if ($('#dashboard-exp-by-categ-chart').length > 0) {
        init_exp_by_categ_chart();
    }

    // Change le mois visualisé
    $(document).on('change', '#transac-date-select', () => {
        reload_dashboard();
    });

    $(document).on('click', '#modal-transac-form *[type="submit"], #modal-transac-bulk-form *[type="submit"]', () => {
        const active_tab = $('#transactions-tabs .nav-link.active').prop('id');
        reload_dashboard().then(() => {
            $(`#${active_tab}`).trigger('click');
        });
    });
});
