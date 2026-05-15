import { formSerializeObject, showFormErrors } from "./helpers/forms.js";

$(function () {
    $(document).on('submit', 'form#categ-edit-form', e => {
        e.preventDefault();

        $.post(
            $('#categ-edit-form').prop('action'),
            formSerializeObject('#categ-edit-form')
        ).done(response => {
            // @FIXME : gérer une possible erreur
            $('#categories-list-wrapper').html(response);
            $('#modal-categ-form .btn-close').trigger('click');
        }).fail(response => {
            showFormErrors('#categ-edit-form', response.responseJSON.errors);
        });
    });

    // Soumet le formulaire de suppression
    $(document).on('submit', '#categ-delete-form', e => {
        e.preventDefault();

        $.ajax({
            method: 'DELETE',
            url: $('#categ-delete-form').prop('action'),
            data: {
                id: $('#categ-delete-form input[name="id"]').val()
            },
            success: response => {
                if (response.deleted) {
                    $('#modal-categ-delete .btn-close').trigger('click');
                    $('#categories-list-wrapper').html(response.view);
                }
            }
        });
    });
});
