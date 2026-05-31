import './helpers/filters.js';
import './helpers/forms.js';

$(function () {
    // Filtres début/fin : change la range de dates sélectionnables
    $(document).on('change', 'input[type="date"]', e => {
        const prop_to_update = $(e.target).prop('name') === 'date_start' ? 'min' : 'max';
        $(e.target).siblings('input').prop(prop_to_update, $(e.target).val());
    });

    /* --- Édition Transaction --- */
    // Initialise le formulaire d'édition de transaction
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
});
