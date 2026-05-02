import './filters.js';
import {formatSQLDate} from "@/helpers.js";

$(function () {
    // Change la range de dates sélectionnables
    $(document).on('change', 'input[type="date"]', e => {
        const prop_to_update = $(e.target).prop('name') === 'date_start' ? 'min' : 'max';
        $(e.target).siblings('input').prop(prop_to_update, $(e.target).val());
    });

    // Renseigne ou vide le formulaire d'édition
    $(document).on('show.bs.modal', '#modal_transac_form', e => {
        const form = $('#modal_transac_form #transac-edit-form');

        if ($(e.relatedTarget).data('action') === 'edit') {
            $(form).find('#transac-id').val($(e.relatedTarget).data('transac-id'));
            $(form).find('#transac-file').removeClass('d-none');
            $(form).find('#transac-line').removeClass('d-none');
            $.get('transactions/get/' + $(e.relatedTarget).data('transac-id')).then(response => {
                for (const i in response.transaction) {
                    $(form).find(`input[name="${i}"], select[name="${i}"]`).val(response.transaction[i]);
                }
                $(form)
                    .find('.modal-title')
                    .text(`Éditer ${response.transaction['type']} du ` +
                        formatSQLDate(response.transaction['occurred_at'])
                    );
            });
        } else {
            $(form).find('#transac-new-benef-wrapper').addClass('d-none');
            $(form).find('#transac-file-wrapper').addClass('d-none');
            $(form).find('#transac-line-wrapper').addClass('d-none');
            $(form).find('input, select').val('');
            $(form).find('.modal-title').text('Ajouter une transaction');
        }
    });

    // Update le champ pretty_name quand un bénéficiaire est sélectionné
    $(document).on('change', '#transac-benef-id', e => {
        $('#transac-benef-pretty').val($('#transac-benef-id option:selected').data('pretty_name'));
    });
});
