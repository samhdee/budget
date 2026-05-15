import { Modal } from "bootstrap";

import './helpers/filters.js';
import { formatSQLDate } from "./helpers/helpers.js";
import { formSerializeObject, showFormErrors } from "./helpers/forms.js";
import { getFilteredList } from "./helpers/filters.js";

$(function () {
    // Filtres début/fin : change la range de dates sélectionnables
    $(document).on('change', 'input[type="date"]', e => {
        const prop_to_update = $(e.target).prop('name') === 'date_start' ? 'min' : 'max';
        $(e.target).siblings('input').prop(prop_to_update, $(e.target).val());
    });

    /* --- Édition Transaction --- */
    // Renseigne ou vide le formulaire d'édition de transac
    $(document).on('show.bs.modal', '#modal-transac-form', e => {
        const open_button = $(e.relatedTarget);
        const form = $('#modal-transac-form #transac-edit-form');
        $('#transac-new-benef-wrapper').removeClass('show');
        $('#transac-benef-id').prop('disabled', '');
        $('#transac-new-benef').val('');

        if ($(open_button).data('action') === 'edit') {
            $('#transac-line-wrapper').removeClass('d-none');
            $('#transac-file-wrapper').removeClass('d-none');

            // Récupère et affiche les infos de la transaction
            // @TODO: Ajouter la gestion d'erreur
            $.get('transactions/get/' + $(open_button).data('transac-id')).then(response => {
                for (const i in response.transaction) {
                    $(form).find(`input[name="${i}"], select[name="${i}"], textarea[name="${i}"]`).val(response.transaction[i]);
                }
                $('#modal-transac-form .modal-title')
                    .text(`Éditer ${response.transaction['type']} du ` +
                        formatSQLDate(response.transaction['occurred_at'])
                    );
            });
        } else {
            // Reset le formulaire
            $('#transac-file-wrapper').addClass('d-none');
            $('#transac-line-wrapper').addClass('d-none');
            $(form).find('input, select').val('');
            $('#modal-transac-form .modal-title').text('Ajouter une transaction');
        }
    });

    // Vide le select Bénéficiaire si on clique sur Nouveau bénéficiaire
    $(document).on('show.bs.collapse', '#transac-new-benef-wrapper', () => {
        // const previous_value = $('#transac-benef-id').val();
        $('#transac-benef-id')
            // @FIXME: Marche pô
            // .data('previous_value', previous_value)
            .prop('disabled', 'disabled')
            .val('');
    });

    // Enlève le disabled du select Bénéficiaire si on cache le champ Nouveau bénéficiaire
    $(document).on('hide.bs.collapse', '#transac-new-benef-wrapper', () => {
        $('#transac-benef-id').prop('disabled', '');
    });

    // Soumet le formulaire
    $(document).on('click', '#transac-form-submit', e => {
        e.preventDefault();

        $.post(
            $('#transac-edit-form').prop('action'),
            formSerializeObject('#transac-edit-form')
        ).done(response => {
            if (typeof response.updated !== 'undefined') {
                $('#modal-transac-form .btn-close').trigger('click');
                getFilteredList($('#transac-list-wrapper .page-item.active .page-link').first().text());
            }
        }).fail(response => {
            showFormErrors('#transac-edit-form', response.responseJSON.errors);
        });
    });

    /* --- Édition Bénéficiaire --- */
    // Renseigne le formulaire d'édition de benef
    $(document).on('show.bs.modal', '#modal-benef-form', e => {
        const open_button = $(e.relatedTarget);
        const form = $('#modal-benef-form #benef-edit-form');
        $(form).find('#benef-id').val($(open_button).data('benef-id'));

        $.get('benefs/get/' + $(open_button).data('benef-id')).then(response => {
            for (const i in response.beneficiary) {
                $(form)
                    .find(`input[name="${i}"], select[name="${i}"], textarea[name="${i}"]`)
                    .val(response.beneficiary[i]);
            }

            $(form)
                .find('.modal-title')
                .text(`Éditer ${response.beneficiary['raw_name']}`);
        });
    });

    // Soumet le formulaire
    $(document).on('submit', 'form#benef-edit-form', e => {
        e.preventDefault();

        $.post(
            $('#benef-edit-form').prop('action'),
            formSerializeObject('#benef-edit-form')
        ).then(response => {
            // @TODO: else : afficher une erreur
            if (response.updated.length > 0) {
                $('#modal-benef-form .btn-close').trigger('click');
                getFilteredList($('#transac-list-wrapper .page-item.active .page-link').first().text());
            }
        }).fail(response => {
            if (typeof response.responseJSON !== 'undefined' && typeof response.responseJSON.errors !== 'undefined') {
                showFormErrors('#benef-edit-form', response.responseJSON.errors);
            }
        });
    });
});
