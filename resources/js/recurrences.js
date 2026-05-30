import './helpers/forms.js';
import './helpers/filters.js';

$(function () {
    $(document).on('show.bs.modal', '#modal-recurrence-transacs-form', e => {
        const open_button = $(e.relatedTarget);
        const modal = $('#modal-recurrence-transacs-form');

        $.get($(open_button).data('url'))
            .done(response => {
                $(modal).find('#recur-transac-beneficiary').text(response.item[0].beneficiary.pretty_name.length > 0
                    ? response.item[0].beneficiary.pretty_name
                    : response.item[0].beneficiary.raw_name
                )
            });
    });
});
