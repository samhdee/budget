import Chart from 'chart.js/auto';

import './helpers/filters.js';
import './helpers/forms.js';

$(function () {
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
                backgroundColor:[
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
});
