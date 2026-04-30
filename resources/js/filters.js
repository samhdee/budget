import {spinner} from "./helpers.js";

$(function () {
    $(document).on('change', '.filters-wrapper select, .filters-wrapper input', e => {
        const wrapper = $(e.target).parents('.filters-wrapper');
        const list_wrapper = $(wrapper).data('target');
        $(list_wrapper).html(spinner());
        const filters = {};

        $(wrapper).find('select, input').each(function () {
            filters[$(this).prop('name')] = $(this).val();
        });

        $.post(
            $(wrapper).data('url'),
            {_token: $('input[name="_token"]').val(), filters},
            response => {
                $(list_wrapper).html(response);
            }
        );
    });

    $(document).on('click', '.filter-reset', e => {
        $('.filters-wrapper select, .filters-wrapper input')
            .val('')
            .trigger('change');
    });
});
