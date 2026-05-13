import './bootstrap';

$(function () {
    $.ajaxSetup({
        beforeSend: (xhr, options) => {
            if (!options.url.startsWith(window.location.origin)) {
                options.url = window.location.origin + '/' + options.url;
            }
        },
        headers: {
            'X-CSRF-TOKEN': $('input[name="_token"]').val()
        }
    });

    const colorpicker = colorPicker.create({
        container: document.getElementById('player-colour-container'),
        color: player_colour,
        preset: [
            // Jaunes
            '#f2e42c', '#e3ca26', '#dbbf33', '#cfaf3a', '#f5c414',
            // oranges
            '#eca222', '#ff8800', '#cc721e',
            // rouges
            '#ff6842','#cc3838', '#e60000', '#e60039',
            // roses
            '#e6006f', '#cf3a5f', '#df6885', '#f63c96', '#df68a7', '#e548e0', '#ce22c8',
            // violets
            '#a752e0', '#a752e0', '#8e57b2',
            // bleus foncés
            '#7064ce', '#6273f9', '#0066ff', '#5a92e7',
            // bleurs clairs
            '#22a2e2', '#33b5cc', '#33ccc2',
            // verts
            '#33cc9e', '#1d9a75', '#1d9a53', '#27d011', '#65a518', '#87e316',
        ],
        usageStatistics: false
    });
});
