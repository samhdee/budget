import { formSerializeObject, showFormErrors } from "./helpers/forms.js";

$(function () {
    // Renseigne ou vide le formulaire d'édition de categ
    $(document).on('show.bs.modal', '#modal-categ-form', e => {
        const open_button = $(e.relatedTarget);
        const form = $('#modal-categ-form #categ-edit-form');

        if ($(open_button).data('action') === 'edit') {
            $('#categ-id').val($(open_button).data('categ-id'));
            $('#categ-file').removeClass('d-none');
            $('#categ-line').removeClass('d-none');

            // Récupère et affiche les infos de la categorie
            // @TODO: Ajouter la gestion d'erreur
            $.get('categories/get/' + $(open_button).data('categ_id')).then(response => {
                for (const i in response.category) {
                    $(form).find(`input[name="${i}"], select[name="${i}"], textarea[name="${i}"]`).val(response.category[i]);
                }
                $(form)
                    .find('.modal-title')
                    .text(`Éditer la catégorie ${response.category['label']}`);
            });
        } else {
            // Reset le formulaire
            $(form).find('input, select').val('');
            $(form).find('.modal-title').text('Ajouter une categorie');
        }
    });

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
