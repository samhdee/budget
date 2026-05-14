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
});
