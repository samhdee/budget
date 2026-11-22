export const spinner = () => {
    return '<div class="d-flex justify-content-center">' +
        '<div class="mt-5 spinner-border text-light" role="status">' +
            '<span class="visually-hidden">Loading...</span>' +
        '</div>' +
    '</div>';
}

export const formatSQLDate = (date_string) => {
    const expl_date = date_string.split('-');
    return `${expl_date[2]}/${expl_date[1]}/${expl_date[0]}`;
}

export const showFlashMessage = (type, message) => {
    $('#alert-message-wrapper').html(`<div class="alert alert-${type} alert-dismissible">
            ${message}
            <button type="button" class="btn btn-close"></button>
        </div>`
    )
}

$(function () {
    // Trie une liste sans filtres
    $(document).on('click', 'a.sort-list-without-filters', e => {
        e.preventDefault();

        $.post(
            $(e.target).prop('href'),
            {
                sort: {
                    column: $(e.currentTarget).data('sort_col'),
                    direction: $(e.currentTarget).data('sort_dir')
                }
            }
        ).done(response => {
            $($(e.target).data('target')).html(response);
        })
    });
})
