<div class="modal fade modal-form" id="modal-transac-delete" tabindex="-1" aria-labelledby="transac-delete-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title fs-4" id="transac-delete-title"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="transac-delete-form" method="DELETE" action="{{ route('transac_delete') }}">
                <div class="modal-body">
                    <div class="d-none alert alert-danger"></div>

                    <input type="hidden" id="transac-id" name="id"/>

                    <div>Supprimer la transaction ?</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="transac-delete-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
