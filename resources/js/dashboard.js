import Chart from 'chart.js/auto';

import './helpers/filters.js';
import './helpers/forms.js';

$(document).ready(() => {
    document.querySelectorAll('.dashboard-chart').forEach((canvas) => {
        const dataset = JSON.parse(canvas.dataset.values);

        let y_options = {
            beginAtZero: true,
            ticks: {
                callback: function (value) {
                    return `${value}${canvas.dataset.unit}`;
                }
            }
        };

        if (typeof canvas.dataset.max !== 'undefined') {
           y_options = {
               ...y_options,
               ...{
                    max: canvas.dataset.max,
               }
           }
        }

        new Chart(canvas, {
            type: canvas.dataset.type,
            data: {
                labels: dataset.labels,
                datasets: [{
                    label: canvas.dataset.title,
                    data: dataset.values
                }],
            },
            options: {
                scales: {
                    y: y_options
                }
            }
        });
    });
});
