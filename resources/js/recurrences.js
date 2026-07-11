import "./helpers/forms.js";
import "./helpers/filters.js";
import { spinner } from "./helpers/helpers.js";

const hydrate_transac_list = (data, template, container) => {
    $(container).empty();

    for (let i in data) {
        const template_clone = $(template).clone();
        $(template_clone).prop('id', '');
        $(template_clone)
            .find(".transac-action")
            .prop('data-transaction_id', data[i].id);
        $(template_clone)
            .find(".recur-transac-date")
            .text(data[i].occurred_at);
        $(template_clone)
            .find(".recur-transac-amount")
            .text(Math.abs(data[i].amount) + '€');

        if (data[i].category !== null) {
            $(template_clone)
                .find(".recur-transac-categ")
                .text(`(${data[i].category.appellation})`);
        }

        $(template_clone).removeClass("d-none");
        $(container).append(template_clone);
    }
}

$(function () {
    // Affiche la modale listant les transactions
    $(document).on("show.bs.modal", "#modal-recurrence-transacs-form", e => {
        const open_button = $(e.relatedTarget);
        const modal = $("#modal-recurrence-transacs-form");
        $(modal)
            .find("#transac-recurrence-id")
            .val($(open_button).data("item_id"));
        $("#modal-recurrence-transacs-form #recur-transac-search-results").empty();
        $('#recurrence-transac-form .alert-danger').addClass('d-none');

        $.get($(open_button).data("url")).done((response) => {
            $(modal)
                .find("#recur-transac-label")
                .text(response.label);
            hydrate_transac_list(response.item, '#recur-transac-template', '#modal-recurrence-transacs-form #recur-transacs-list');
        });
    });

    // Fetch la liste des transactions du bénéficiaire de la récurrence
    $(document).on("click", "#trigger-search-transacs", e => {
        e.preventDefault();
        const recurrence_id = $("#transac-recurrence-id").val();
        const search_results = $("#modal-recurrence-transacs-form #recur-transac-search-results");
        $(search_results).html(spinner());

        $.get(`recurrences/${recurrence_id}/transacs/search`).done(
            response => {
                $(search_results).empty();

                if (response.data.length === 0) {
                    $(search_results).html('<div class="text-center fst-italic">' +
                        '<i class="fas fa-ban"></i> Aucun résultat' +
                    '</div>');
                } else {
                    hydrate_transac_list(response.data, '#recur-add-transac-template', '#recur-transac-search-results');
                }
            },
        );
    });

    // Ajoute une transaction à la récurrence
    $(document).on('click', '#modal-recurrence-transacs-form .transac-add', e => {
        e.preventDefault();

        $('#recurrence-transac-form .alert-danger').addClass('d-none').empty();
        $("#modal-recurrence-transacs-form #recur-transacs-list").html(spinner());

        const button = $(e.currentTarget);
        const recurrence_id = $('#modal-recurrence-transacs-form #transac-recurrence-id').val();
        const transaction_id = $(button).prop('data-transaction_id');

        $.get(`recurrences/${recurrence_id}/transac/add/${transaction_id}`)
            .done(response => {
                $.get('recurrences/list').done(response => {
                    $('#recurrences-active-tab-pane').html(response);
                });

                hydrate_transac_list(response.items_with, '#recur-transac-template', '#recur-transacs-list');

                if (!$('#recur-transac-search-results').hasClass('d-none')) {
                    hydrate_transac_list(response.items_without, '#recur-add-transac-template', "#recur-transac-search-results");
                }
            }).fail(response => {
                $('#recurrence-transac-form .alert-danger')
                    .removeClass('d-none')
                    .text(response.responseJSON.message);
            });
    });

    // Retire une transaction de la liste
    $(document).on('click', '#recur-transacs-list .transac-remove', e => {
        e.preventDefault();

        $('#recurrence-transac-form .alert-danger').addClass('d-none').empty();
        $("#modal-recurrence-transacs-form #recur-transacs-list").html(spinner());

        const button = $(e.currentTarget);
        const recurrence_id = $('#modal-recurrence-transacs-form #transac-recurrence-id').val();
        const transaction_id = $(button).prop('data-transaction_id');

        $.get(`recurrences/${recurrence_id}/transac/remove/${transaction_id}`).done(response => {
            $.get('recurrences/list').done(response => {
                $('#recurrences-active-tab-pane').html(response);
            });
            hydrate_transac_list(response.items_with, '#recur-transac-template', "#recur-transacs-list");

            if (!$('#recur-transac-search-results').hasClass('d-none')) {
                hydrate_transac_list(response.items_without, '#recur-add-transac-template', "#recur-transac-search-results");
            }
        });
    });
});
