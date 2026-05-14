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
});
