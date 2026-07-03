import "./helpers/forms.js";
import "./helpers/filters.js";
import { spinner } from "./helpers/helpers.js";

const hydrate_transac_list = data => {
    $("#modal-recurrence-transacs-form #recur-transacs-list").empty();
    const template = $("#recur-transac-template");

    for (let i in data) {
        const template_clone = $(template).clone();
        $(template_clone).prop('id', '');
        $(template_clone)
            .find(".transac-remove")
            .data('recurrence_id', data[i].id);
        $(template_clone)
            .find(".recur-transac-date")
            .text(data[i].occurred_at);
        $(template_clone)
            .find(".recur-transac-amount")
            .text(Math.abs(data[i].amount) + '€');

        if (data[i].category !== null) {
            $(template_clone)
                .find(".recur-transac-categ")
                .text(data[i].category.appellation);
        }

        $(template_clone).removeClass("d-none");
        $("#recur-transacs-list").append(template_clone);
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
                .find("#recur-transac-beneficiary")
                .text(
                    response.items[0].beneficiary.pretty_name !== null
                        ? response.items[0].beneficiary.pretty_name
                        : response.items[0].beneficiary.raw_name,
                );

            hydrate_transac_list(response.items);
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
                    for (let i in response.data) {
                        const amount = Math.abs(response.data[i].amount);

                        $(search_results).append(
                            `<div class="transac-search-result-item d-flex justify-content-between">
                                <div>
                                    ${response.data[i].occurred_at} : ${amount}€
                                </div>

                                <div>
                                    <button
                                        type="button"
                                        class="btn btn-sm btn-success transac-item-add"
                                        data-transaction_id="${response.data[i].id}"
                                    >
                                        <i class="fa fa-plus-circle"></i>
                                    </button>
                                </div>
                            </div>`
                        );
                    }
                }
            },
        );
    });

    // Ajoute une transaction à la récurrence
    $(document).on('click', '#modal-recurrence-transacs-form .transac-item-add', e => {
        e.preventDefault();
        const button = $(e.currentTarget);
        const recurrence_id = $('#modal-recurrence-transacs-form #transac-recurrence-id').val();
        const transaction_id = $(button).data('transaction_id');
        $('#recurrence-transac-form .alert-danger').addClass('d-none').empty();
        $("#modal-recurrence-transacs-form #recur-transacs-list").html(spinner());

        $.get(`recurrences/${recurrence_id}/transac/add/${transaction_id}`)
            .done(response => {
                hydrate_transac_list(response.items);
                $(button).parents('.transac-search-result-item').remove();
            }).fail(response => {
                $('#recurrence-transac-form .alert-danger')
                    .removeClass('d-none')
                    .text(response.responseJSON.message);
            });
    });
});
