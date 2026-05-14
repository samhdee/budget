/**
 * Renvoie un objet contenant les input du formulaire
 * @param form_selector
 * @returns {{}}
 */
export const formSerializeObject = function (form_selector) {
    const results = {},
        arr = $(form_selector).serializeArray();

    for (let i = 0, len = arr.length; i < len; i++) {
        const obj = arr[i];

        // Check if results have a property with given name
        if (results.hasOwnProperty(obj.name)) {
            // Check if given object is an array
            if (!results[obj.name].push) {
                results[obj.name] = [results[obj.name]];
            }
            results[obj.name].push(obj.value || '');
        } else {
            results[obj.name] = obj.value || '';
        }
    }

    return results;
}

export function showFormErrors(container, errors) {
    $(`${container} .form-error-message`).remove();
    $(`${container} .form-error`).removeClass('form-error');

    for (let i in errors) {
        const field = $(
            `${container} input[name="${i}"], ` +
            `${container} select[name="${i}"], ` +
            `${container} textarea[name="${i}"]`
        ).first();

        const field_parent = $(field).parents('.form-field');
        $(field_parent).addClass('form-error');

        if ($(field_parent).find('label .error-icon').length === 0) {
            $(field_parent)
                .find('label')
                .append(
                    '<i class="fas fa-exclamation-circle error-icon ms-1"></i>'
                );
        }

        $(field_parent).append(`<div class="form-error-message form-text">${errors[i].join(' ')}</div>`);
    }
}

$(function () {
    // Renseigne ou vide le formulaire d'édition de categ
    $(document).on('show.bs.modal', '.modal-form', e => {
        const open_button = $(e.relatedTarget);
        const form = $(e.currentTarget).find('form');
        const type = $(open_button).data('type');

        $(form).find('.form-error-message').remove();
        $(form).find('.form-error').removeClass('form-error');

        if ($(open_button).data('action') === 'edit') {
            $(form).find('input[name="id"]').val($(open_button).data('item_id'));

            // Récupère et affiche les infos de la categorie
            // @TODO: Ajouter la gestion d'erreur
            $.get($(open_button).data('url')).then(response => {
                for (const i in response.item) {
                    $(form).find(`input[name="${i}"], select[name="${i}"], textarea[name="${i}"]`).val(response.item[i]);
                }
                $(form)
                    .find('.modal-title')
                    .html(`Éditer la ${type} <span class="fst-italic">${response.item['appellation']}</span>`);
            });
        } else {
            // Reset le formulaire
            $(form).find('input, select').val('');
            $(form).find('.modal-title').text(`Ajouter une ${type}`);
        }
    });
});
