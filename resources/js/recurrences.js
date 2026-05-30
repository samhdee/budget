import './helpers/forms.js';
import './helpers/filters.js';

$(function () {
    $(document).on('show.bs.modal', '#modal-recurrence-transacs-form', e => {
        const open_button = $(e.relatedTarget);
        const modal = $('#modal-recurrence-transacs-form');
        $(modal).find('#transac-recurrence-id').val($(open_button).data('item_id'));
        $(modal).find('#recur-transac-template:not(.d-none)').remove();

        $.get($(open_button).data('url'))
            .done(response => {
                $(modal).find('#recur-transac-beneficiary').text(response.item[0].beneficiary.pretty_name.length > 0
                    ? response.item[0].beneficiary.pretty_name
                    : response.item[0].beneficiary.raw_name
                );

                const template = $('#recur-transac-template');

                for (let i in response.item) {
                    const template_clone = $(template).clone();
                    $(template_clone).find('.recur-transac-check').val(response.item[i].id);
                    $(template_clone).find('.recur-transac-date').text(response.item[i].occurred_at);
                    const amount = Math.abs(response.item[i].amount);
                    $(template_clone).find('.recur-transac-amount').text(`${amount}€`);

                    if (response.item[i].category !== null) {
                        $(template_clone).find('.recur-transac-categ').text(response.item[i].category.appellation);
                    }

                    $(template_clone).removeClass('d-none');
                    $('#recurrence-transac-form').append(template_clone);
                }
            });
    });
});
