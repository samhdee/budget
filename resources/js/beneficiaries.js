import './helpers/filters.js';
import './helpers/forms.js';
import {getFilteredList} from "./helpers/filters.js";
import {showFlashMessage} from "./helpers/helpers.js";

$(function() {
    $(document).on('show.bs.modal', '#modal-benef-form', e => {
        const action = $(e.relatedTarget).data('action');

        if (action === 'edit') {
            $('#benef-raw-name').prop('disabled', 'disabled');
        } else if (action === 'create') {
            $('#benef-raw-name').prop('disabled', '');
        }
    });

    $(document).on('click', '.sync-categories', e => {
        e.preventDefault();

        if (confirm('Lier toutes les transactions des bénéficiaires à leurs catégories ?')) {
            $.post(
                $(e.currentTarget).data('url'),
                {benef_ids: [$(e.currentTarget).data('benef_id')]},
                response => {
                const page = $('.pagination-wrapper .page-item.active .page-link').length > 0
                    ? $('.pagination-wrapper .page-item.active .page-link').first().text()
                    : null;
                getFilteredList(page);
                showFlashMessage('success', `${response.updated} transaction(s) ont été mises à jour !`);
            })
            .fail(response => {
                showFlashMessage('danger', typeof response.responseJSON.message !== 'undefined'
                    ? response.responseJSON.message
                    :'Une erreur inattendue est survenue');
            });
        }
    });
    $(document).on('change', '#bulk-select-all, .bulk-select', e => {
        if ($('.bulk-select:checked').length > 0) {
            $('#bulk-action-wrapper').removeClass('d-none');
        } else {
            $('#bulk-action-wrapper').addClass('d-none');
        }
    });

    $(document).on('change', '#bulk-select-all', () => {
        if ($('#bulk-select-all').is(':checked')) {
            $('.bulk-select').prop('checked', 'checked')
        } else {
            $('.bulk-select').prop('checked', '');
        }

        $('.bulk-select').trigger('change');
    });

    $(document).on('click', '#bulk-sync-all', () => {
        if (confirm('Lier toutes les transactions des bénéficiaires à leurs catégories ?')) {
            const benef_ids = [];

            $('.bulk-select:checked').each((i, el) => {
                benef_ids.push($(el).data('benef_id'));
            });

            $.post($('#bulk-sync-all').data('url'), {benef_ids}, response => {

            });
        }
    });
});
