import './bootstrap';
import '../../node_modules/@fortawesome/fontawesome-free/js/all';
import $ from 'jquery';

import.meta.glob([
  '../fonts/**',
]);

window.jQuery = window.$ = $;

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
