import { formSerializeObject, showFormErrors } from "./helpers/forms.js";

$(function () {
    $(document).on('submit', 'form#label-edit-form', e => {
        e.preventDefault();

        $.post(
            $('#label-edit-form').prop('action'),
            formSerializeObject('#label-edit-form')
        ).done(response => {
            // @FIXME : gérer une possible erreur
            $('#labels-list-wrapper').html(response);
            $('#modal-label-form .btn-close').trigger('click');
        }).fail(response => {
            showFormErrors('#label-edit-form', response.responseJSON.errors);
        });
    });
});
