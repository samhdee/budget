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
    // Initialise le formulaire d'édition de masse
    $(document).on('show.bs.modal', '#modal-transac-bulk-form', () => {
        $('input[name^="transac_ids"]').remove();
        $('#modal-transac-bulk-form input, #modal-transac-bulk-form select').val('');
        $('#modal-transac-bulk-form .form-error').removeClass('form-error');
        $('#modal-transac-bulk-form .form-message').remove();
        $('#transac-bulk-benef-id').select2({
            dropdownParent: $('#modal-transac-bulk-form')
        });

        $('.bulk-select:checked').each((i, el) => {
            $('#transac-bulk-edit-form')
                .prepend(`<input type="hidden" name="transac_ids[]" value="${$(el).data('transac_id')}" />`);
        });
    });

    // Soumet le formulaire d'édition de mâsse
    $(document).on('click', '#transac-bulk-form-submit', e => {
        e.preventDefault();
        const form = '#transac-bulk-edit-form';
        const modal = '#modal-transac-bulk-form';
        $('#modal-transac-bulk-form .form-error').removeClass('form-error');
        $('#modal-transac-bulk-form .form-message').remove();

        $.post(
            $(form).prop('action'),
            formSerializeObject(form),
            response => {
                if (typeof response.updated !== 'undefined') {
                    $(modal).find('.btn-close').trigger('click');
                    getFilteredList($('.pagination').length > 0 ? $('.pagination .page-item.active .page-link').text() : null);
                }
            }
        ).fail(response => {
            if (typeof response.responseJSON.message !== 'undefined') {
                $(modal).find('.alert-danger')
                    .removeClass('d-none')
                    .html(response.responseJSON.message);
            }

            if (typeof response.responseJSON.errors !== 'undefined') {
                showFormErrors(form, response.responseJSON.errors);
            }
        });
    });
    $(document).on('show.bs.modal', '#modal-transac-form', e => {
        $('#transac-benef-id').select2({
            dropdownParent: $('#modal-transac-form')
        });

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
});
