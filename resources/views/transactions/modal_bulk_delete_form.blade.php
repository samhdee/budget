@php
    use App\Enums\TransactionType;
    use App\Models\Beneficiary;
    use App\Models\Category;
@endphp

<div
    id="modal-transac-bulk-delete-form"
    class="modal fade modal-bulk-form"
    aria-labelledby="transac-bulk-delete-form-title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="transac-bulk-delete-form-title" class="modal-title fs-4"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="transac-bulk-delete-form" method="POST" action="{{ route('transac_bulk_delete') }}">
                <div class="modal-body">
                    <div class="d-none alert alert-danger"></div>

                    <div>
                        Supprimer <span id="transac-bulk-delete-nb"></span> transaction(s) ?
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="transac-bulk-delete-form-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
