import './helpers/filters.js';
import './helpers/forms.js';
import { formSerializeObject, showFormErrors } from "./helpers/forms.js";
import { getFilteredList } from "./helpers/filters.js";

$(function () {
    // Filtres début/fin : change la range de dates sélectionnables
    $(document).on('change', 'input[type="date"]', e => {
        const prop_to_update = $(e.target).prop('name') === 'date_start' ? 'min' : 'max';
        $(e.target).siblings('input').prop(prop_to_update, $(e.target).val());
    });

    /* --- Édition Transaction --- */
    $(document).on('show.bs.modal', '#modal-transac-form', e => {
        if ($(e.relatedTarget).data('action') === 'edit') {
            $('#transac-line-wrapper').removeClass('d-none');
            $('#transac-file-wrapper').removeClass('d-none');
        } else {
            $('#transac-line-wrapper').addClass('d-none');
            $('#transac-file-wrapper').addClass('d-none');
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

    // Soumet le formulaire de suppression
    $(document).on('submit', '#transac-delete-form', e => {
        e.preventDefault();

        $.ajax({
            method: 'DELETE',
            url: $('#transac-delete-form').prop('action'),
            data: {
                id: $('#transac-delete-form input[name="id"]').val()
            },
            success: response => {
                if (response.deleted) {
                    $('#modal-transac-delete .btn-close').trigger('click');
                    getFilteredList($('#transac-list-wrapper .page-item.active .page-link').first().text());
                }
            }
        });
    });

    /* --- Édition Bénéficiaire --- */
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
