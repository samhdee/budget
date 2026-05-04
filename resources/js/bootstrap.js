import * as bootstrap from 'bootstrap'
import '@fortawesome/fontawesome-free/js/all';
import $ from 'jquery';

import.meta.glob([
  '../fonts/**',
]);

window.jQuery = window.$ = $;
document.addEventListener('DOMContentLoaded', () => {
    const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
    document.querySelectorAll('[data-bs-toggle="tooltip"]').forEach(el => new bootstrap.Tooltip(el, {
      container: 'body'
    }));
});
