@php
    use App\Models\Beneficiary;
@endphp

<div
    id="modal-recurrence-transacs-form"
    class="modal fade"
    tabindex="-1"
    aria-labelledby="recurrence-transacs-form-title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="recurrence-transacs-form-title" class="modal-title fs-4">
                    Bénéficiaire : <span id="recur-transac-beneficiary"></span>
                </h3>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="recurrence-transac-form" method="POST" action="{{ route('recurrences_store') }}" data-list="#recurrences-list-wrapper">
                    <div class="d-none alert alert-danger"></div>

                    <input type="hidden" id="transac-recurrence-id" name="id" />

                    <div id="recur-transac-template" class="d-none mt-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="card-title recur-transac-date"></div>

                                <div class="d-flex justify-content-between">
                                    <div>
                                        <span class="recur-transac-amount"></span>
                                        <span class="ms-1 recur-transac-categ"></span>
                                    </div>

                                    <div>
                                        <input class="form-check-input recur-transac-check" type="checkbox" role="switch" checked />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="recurrence-form-submit" type="submit" class="btn btn-success">Envoyer</button>
            </div>
        </div>
    </div>
</div>
