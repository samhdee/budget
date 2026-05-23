import './bootstrap';
import select2 from 'select2';

$(function () {
    if ($('.select2').length > 0) {
        select2();
        $('.select2').select2();
    }

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
