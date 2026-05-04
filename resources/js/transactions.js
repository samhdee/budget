import { Modal } from "bootstrap";

import './filters.js';
import { formatSQLDate, formSerializeObject } from "./helpers.js";

$(function () {
    // Change la range de dates sélectionnables
    $(document).on('change', 'input[type="date"]', e => {
        const prop_to_update = $(e.target).prop('name') === 'date_start' ? 'min' : 'max';
        $(e.target).siblings('input').prop(prop_to_update, $(e.target).val());
    });

    // Renseigne ou vide le formulaire d'édition
    $(document).on('show.bs.modal', '#modal-transac-form', e => {
        const open_button = $(e.relatedTarget);
        const form = $('#modal-transac-form #transac-edit-form');
        $('#transac-new-benef-wrapper').removeClass('show');
        $('#transac-benef-id').prop('disabled', '');
        $('#transac-new-benef').val('');

        if ($(open_button).data('action') === 'edit') {
            $('#transac-id').val($(open_button).data('transac-id'));
            $('#transac-file').removeClass('d-none');
            $('#transac-line').removeClass('d-none');

            // Récupère et affiche les infos de la transactions
            // @TODO: Ajouter la gestion d'erreur
            $.get('transactions/get/' + $(open_button).data('transac-id')).then(response => {
                for (const i in response.transaction) {
                    $(form).find(`input[name="${i}"], select[name="${i}"], textarea[name="${i}"]`).val(response.transaction[i]);
                }
                $(form)
                    .find('.modal-title')
                    .text(`Éditer ${response.transaction['type']} du ` +
                        formatSQLDate(response.transaction['occurred_at'])
                    );
            });
        } else {
            // Reset le formulaire
            $('#transac-file-wrapper').addClass('d-none');
            $('#transac-line-wrapper').addClass('d-none');
            $(form).find('input, select').val('');
            $(form).find('.modal-title').text('Ajouter une transaction');
        }
    });

    // Vide le select Bénéficiaire si on clique sur Nouveau bénéficiaire
    $(document).on('show.bs.collapse', '#transac-new-benef-wrapper', () => {
        const previous_value = $('#transac-benef-id').val();
        $('#transac-benef-id')
            // @FIXME: Marche pô
            .data('previous_value', previous_value)
            .prop('disabled', 'disabled')
            .val('');
    });

    // Enlève le disabled du select Bénéficiaire si on cache le champ Nouveau bénéficiaire
    $(document).on('hide.bs.collapse', '#transac-new-benef-wrapper', () => {
        $('#transac-benef-id').prop('disabled', '');
    });

    // Soumet le formulaire
    $(document).on('submit', 'form#transac-edit-form', e => {
        e.preventDefault();
        console.log(formSerializeObject('#transac-edit-form'));
        $.post(
            $('#transac-edit-form').prop('action'),
            formSerializeObject('#transac-edit-form'),
            response => {
                $('#transac-filter-type').trigger('change');
                Modal.getInstance('#modal-transac-form').hide();
            }
        );
    });
});
