import './helpers/filters.js';
import './helpers/forms.js';

import { getFilteredList } from "./helpers/filters.js";
import { showFlashMessage } from "./helpers/helpers.js";

$(function() {

    // Lie les transactions des bénéfs à la catégorie par défaut du bénéf
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
});
