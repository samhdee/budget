import { getFilteredList } from "./filters.js";
import { showFlashMessage } from "./helpers.js";

/**
 * Renvoie un objet contenant les input du formulaire
 * @param form_selector
 * @returns {{}}
 */
export const formSerializeObject = function (form_selector) {
    const results = {},
        serialized_form = $(form_selector).serializeArray();

    for (let i = 0, len = serialized_form.length; i < len; i++) {
        const form_element = serialized_form[i];

        // Check if results have a property with given name
        if (results.hasOwnProperty(form_element.name)) {
            // Check if given object is an array
            if (!results[form_element.name].push) {
                results[form_element.name] = [results[form_element.name]];
            }

            results[form_element.name].push(form_element.value || '');
        } else {
            results[form_element.name] = form_element.value || '';
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
    $(document).on('shown.bs.modal', '.modal-form', e => {
        const open_button = $(e.relatedTarget);
        const form = $(e.currentTarget).find('form');
        const type = $(open_button).data('type');

        $(e.currentTarget).find('.alert-danger').addClass('d-none');
        $(form).find('.form-error-message').remove();
        $(form).find('.form-error').removeClass('form-error');

        if ($(open_button).data('action') === 'create') {
            // Reset le formulaire
            $(form).find('input, select, textarea').val('');
            $(form).find('.modal-title').text(`Ajouter ${type}`);

            if ($(form).find('.select2')) {
                $(form).find('.select2').select2({dropdownParent: '#' + $(e.currentTarget).prop('id')});
                $(form).find('.select2').trigger('change.select2');
            }
        } else if (typeof $(open_button).data('url') !== 'undefined') {
            // Récupère et affiche les infos de la categorie
            // @TODO: Ajouter la gestion d'erreur
            $.get($(open_button).data('url')).then(response => {
                for (const i in response.item) {
                    const form_element = $(form).find(`input[name="${i}"], select[name="${i}"], textarea[name="${i}"]`);

                    if ($(form_element).prop('type') === 'checkbox') {
                        $(form_element).prop('checked', response.item[i] == '1' ? 'checked' : '');
                    } else if ($(form_element).prop('multiple')) {
                        $(form_element).find('option:selected').prop('selected', '');

                        for (const j in response.item[i]) {
                            $(form_element).find(`option[value="${response.item[i][j].id}"]`).prop('selected', true);
                        }
                    } else {
                        $(form_element).val(response.item[i]);
                    }
                }

                let title = ($(open_button).data('action') === 'delete' ? 'Supprimer ' : 'Éditer ');

                if (typeof type !== 'undefined') {
                    title += type;
                }

                if (typeof response.item['appellation'] !== 'undefined') {
                    title += ` <span class="fst-italic">${response.item['appellation']}</span>`;
                }

                $(e.currentTarget).find('.modal-title').html(title);

                if ($(form).find('.select2')) {
                    $(form).find('.select2').select2({dropdownParent: '#' + $(e.currentTarget).prop('id')});
                    $(form).find('.select2').trigger('change.select2');
                }
            });
        }
    });

    // Initialise le formulaire d'édition de mâsse
    $(document).on('show.bs.modal', '.modal-bulk-form', e => {
        const modal = $(e.relatedTarget).data('bs-target');
        $(modal).find('input[name^="item_ids"]').remove();
        $(modal).find('form input, form select, form textarea').val('').prop('checked', '');

        $('.bulk-select:checked').each((i, el) => {
            $(modal).find('form')
                .prepend(`<input type="hidden" name="item_ids[]" value="${$(el).data('item_id')}" />`);
        });
    });

    // Soumet les formulaires en modale
    $(document).on('click', '.modal-form *[type="submit"], .modal-bulk-form *[type="submit"]', e => {
        e.preventDefault();
        const modal = $(e.currentTarget).parents('.modal-form, .modal-bulk-form');
        const form = $(modal).find('form');
        $(modal).find('.form-error-message').remove();
        $(modal).find('.form-error').removeClass('form-error');
        $(modal).find('.alert-danger').addClass('d-none');

        $.post(
            $(form).prop('action'),
            formSerializeObject(form)
        ).done(response => {
            if (typeof response.errors === 'undefined') {
                $(modal).find('.btn-close').trigger('click');

                if (typeof response.view !== 'undefined') {
                    if (typeof $(form).data('list') !== 'undefined') {
                        $($(form).data('list')).html(response.view);
                    } else if (typeof response.view !== 'undefined') {
                        $('.list-wrapper').html(response.view);
                    }
                } else {
                    const page = $('.pagination-wrapper').length > 0
                        ? $('.pagination-wrapper .page-item.active .page-link').first().text()
                        : null;
                    getFilteredList(page);
                }
            }
        }).fail(response => {
            if (typeof response.responseJSON.errors !== 'undefined') {
                showFormErrors(`#${$(form).attr('id')}`, response.responseJSON.errors);
            } else {
                $(form).find('.alert-danger')
                    .html(typeof response.responseJSON.message !== 'undefined'
                        ? response.responseJSON.message
                        : 'Une erreur inattendue est survenue.'
                    )
                    .removeClass('d-none');
            }
        });
    });

    // Coche toutes les checkboxes
    $(document).on('change', '.bulk-select-all', e => {
        const wrapper = $(e.target).parents('.content-wrapper');

        if ($('.bulk-select-all').is(':checked')) {
            $(wrapper).find('.bulk-select').prop('checked', 'checked')
        } else {
            $(wrapper).find('.bulk-select').prop('checked', '');
        }

        $(wrapper).find('.bulk-select').trigger('change');
    });

    // Fait apparaître ou disparaître les boutons d'actions groupées
    $(document).on('change', '.bulk-select-all, .bulk-select', e => {
        const wrapper = $(e.target).parents('.content-wrapper');

        if ($(wrapper).find('.bulk-select:checked').length > 0) {
            $(wrapper).find('.bulk-action-wrapper').removeClass('d-none');
        } else {
            $(wrapper).find('.bulk-action-wrapper').addClass('d-none');
        }
    });

    // Affiche un confirm et appelle l'URL demandée
    $(document).on('click', '.btn-action.confirm-before-action', e => {
        e.preventDefault();
        const button = e.currentTarget;

        if (confirm($(button).data('message'))) {
            $.get($(button).data('url')).then(response => {
                if (typeof response.message !== 'undefined') {
                    showFlashMessage('success', typeof response.message !== 'undefined'
                        ? response.message
                        : 'Succès !'
                    );
                }

                if (typeof response.view !== 'undefined') {
                    $($(button).data('list')).html(response.view);
                } else if ($('.filters-wrapper').length > 0) {
                    getFilteredList($('.pagination-wrapper').length > 0
                        ? $('.pagination-wrapper .page-item.active .page-link').first().text()
                        : null
                    );
                }
            }).fail(response => {
                showFlashMessage(
                    'danger',
                    typeof response.responseJSON.message !== 'undefined' ? response.responseJSON.message : 'Une erreur est survenue.'
                );
            });
        }
    });
});
