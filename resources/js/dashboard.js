import Chart from 'chart.js/auto';

import './helpers/filters.js';
import './helpers/forms.js';

function get_bar_chart(element_id) {
    const canvas = document.getElementById(element_id);
    const dataset = JSON.parse(canvas.dataset.values);

    new Chart(canvas, {
        type: 'bar',
        data: {
            labels: dataset.labels,
            datasets: [{
                axis: 'x',
                label: canvas.dataset.title,
                data: dataset.values,
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
                            return `${value}${canvas.dataset.unit}`;
                        }
                    },
                }
            }
        }
    });
}

function get_pie_chart(element_id) {
    const canvas_exp_by_label = document.getElementById(element_id);
    const exp_by_label_dataset = JSON.parse(canvas_exp_by_label.dataset.values);

    new Chart(canvas_exp_by_label, {
        type: 'pie',
        data: {
            labels: exp_by_label_dataset.labels,
            datasets: [{
                label: canvas_exp_by_label.dataset.title,
                data: exp_by_label_dataset.values,
            }],
        },
    });
}

const init_rev_exp_chart = () => {
    get_bar_chart('dashboard-rev-exp-chart');
};

const init_projection_chart = () => {
    get_bar_chart('dashboard-projection-chart');
};

const init_exp_by_categ_chart = () => {
    get_pie_chart('dashboard-exp-by-categ-chart');
};

const init_exp_by_label_chart = () => {
    get_pie_chart('dashboard-exp-by-label-chart');
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

            if ($('#dashboard-exp-by-label-chart').length > 0) {
                init_exp_by_label_chart();
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

    if ($('#dashboard-exp-by-label-chart').length > 0) {
        init_exp_by_label_chart();
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
