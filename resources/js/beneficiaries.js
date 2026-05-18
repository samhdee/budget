import './helpers/filters.js';
import './helpers/forms.js';
import {getFilteredList} from "./helpers/filters.js";
import {showFlashMessage} from "./helpers/helpers.js";
import {formSerializeObject} from "./helpers/forms.js";

$(function() {
    // Active/désactive le champ raw_name
    $(document).on('show.bs.modal', '#modal-benef-form', e => {
        const action = $(e.relatedTarget).data('action');

        if (action === 'edit') {
            $('#benef-raw-name').prop('disabled', 'disabled');
        } else if (action === 'create') {
            $('#benef-raw-name').prop('disabled', '');
        }
    });

    // Fait apparaître ou disparaître les boutons d'actions groupées
    $(document).on('change', '#bulk-select-all, .bulk-select', e => {
        if ($('.bulk-select:checked').length > 0) {
            $('#bulk-action-wrapper').removeClass('d-none');
        } else {
            $('#bulk-action-wrapper').addClass('d-none');
        }
    });

    // Coche toutes les checkboxes
    $(document).on('change', '#bulk-select-all', () => {
        if ($('#bulk-select-all').is(':checked')) {
            $('.bulk-select').prop('checked', 'checked')
        } else {
            $('.bulk-select').prop('checked', '');
        }

        $('.bulk-select').trigger('change');
    });

    // Synchronise les catégories de toutes les lignes sélectionnées
    $(document).on('click', '#bulk-sync-all', () => {
        if (confirm('Lier toutes les transactions des bénéficiaires à leurs catégories ?')) {
            const benef_ids = [];

            $('.bulk-select:checked').each((i, el) => {
                benef_ids.push($(el).data('benef_id'));
            });

            $.post($('#bulk-sync-all').data('url'), {benef_ids}, response => {
                showFlashMessage('success', `${response.updated} transaction(s) mise(s) à jour sur ${$('.bulk-select').length} !`);
                getFilteredList($('.pagination-wrapper .page-item.active .page-link').length > 0
                    ? $('.pagination-wrapper .page-item.active .page-link').text()
                    : null
                );
            });
        }
    });

    // Initialise le formulaire d'assignation de catégories en masse
    $(document).on('show.bs.modal', '#modal-benef-bulk-form', () => {
        $('input[name^="benef_ids"]').remove();
        $('#benef-bulk-category-id').val('');

        $('.bulk-select:checked').each((i, el) => {
            $('#modal-benef-bulk-form .modal-body').prepend(`<input type="hidden" name="benef_ids[]" value="${$(el).data('benef_id')}" />`);
        });
    });

    // Soumet le formulaire d'édition de masse
    $(document).on('submit', '#benef-bulk-edit-form', e => {
        e.preventDefault();

        $.post(
            $('#benef-bulk-edit-form').prop('action'),
            formSerializeObject('#benef-bulk-edit-form'),
            response => {
                $('#modal-benef-bulk-form .btn-close').trigger('click');
                showFlashMessage('success', `${response.updated} bénéficiaire (s) mis à jour sur ${$('.bulk-select').length} !`);
                getFilteredList($('.pagination-wrapper .page-item.active .page-link').length > 0
                    ? $('.pagination-wrapper .page-item.active .page-link').text()
                    : null
                );
            }
        ).fail(response => {
            showFlashMessage('danger', typeof response.responseJSON.message !== 'undefined'
                ? response.responseJSON.message
                : 'Une erreur inattendue s\'est produite.'
            );
        });
    });

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
