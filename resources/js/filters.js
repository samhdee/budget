import {spinner} from "./helpers.js";

$(function () {
    // Déclenche le filtrage
    $(document).on('change keyup', '.filters-wrapper select, .filters-wrapper input', e => {
        const input = $(e.target);

        if ($(input).is('[type="text"]') && ($(input).val().length > 0 && $(input).val().length < 3)) {
            return;
        }

        const wrapper = $(e.target).parents('.filters-wrapper');
        const list_wrapper = $(wrapper).data('target');
        $(list_wrapper).html(spinner());
        const filters = {};

        $(wrapper).find('select, input').each(function () {
            filters[$(this).prop('name')] = $(this).val();
        });

        $.post(
            $(wrapper).data('url'),
            {filters},
            response => {
                $(list_wrapper).html(response);
            }
        );
    });

    // Reset un filtre
    $(document).on('click', '.filter-reset', e => {
        $($(e.currentTarget).data('target')).val('').trigger('change');
    });

    // Cache un bouton reset de filtre
    $(document).on('change keyup', '.filter-wrapper.with-reset input', e => {
        const filter = $(e.currentTarget);

        if ($(filter).val().length > 0) {
            $(filter).siblings('.filter-reset').removeClass('d-none');
        } else {
            $(filter).siblings('.filter-reset').addClass('d-none');
        }
    });

    // Reset tous les filtres
    $(document).on('click', '.all-filter-reset', e => {
        $('.filters-wrapper select, .filters-wrapper input')
            .val('')
            .trigger('change');
    });
});
