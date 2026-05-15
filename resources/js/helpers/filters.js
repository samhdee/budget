import { spinner } from "./helpers.js";

export const getFilteredList = (page = null) => {
    const wrapper = $('.filters-wrapper');
    const list_wrapper = $(wrapper).data('target');
    $(list_wrapper).html(spinner());
    const filters = {};

    $(wrapper).find('select, input').each(function () {
        filters[$(this).prop('name')] = $(this).val();
    });

    let url = $(wrapper).data('url');

    // @FIXME: Récupérer la page active
    if (page) {
        url += `?page=${page}`;
    }

    $.post(
        url,
        {filters},
        response => {
            $(list_wrapper).html(response);
        }
    );
}

$(function () {
    // Déclenche le filtrage
    $(document).on('change keyup', '.filters-wrapper select, .filters-wrapper input', e => {
        const input = $(e.target);

        if ($(input).is('[type="text"]') && ($(input).val().length > 0 && $(input).val().length < 3)) {
            return;
        }

        getFilteredList();
    });

    // Pagination
    $(document).on('click', '.pagination-wrapper a.page-link', e => {
        e.preventDefault();
        getFilteredList($(e.currentTarget).data('page'));
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
    $(document).on('click', '.all-filter-reset', () => {
        $('.filters-wrapper select, .filters-wrapper input')
            .val('')
            .trigger('change');
    });
});
