<div
    id="modal-label-delete"
    class="modal fade modal-form"
    tabindex="-1"
    aria-labelledby="label-delete-title"
    aria-hidden="true"
>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="label-delete-form" method="DELETE" action="{{ route('label_delete') }}">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="label-delete-title">
                        Effacer le label <span id="label-delete-appellation"></span> ?
                    </h3>

                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="label-id" name="id"/>

                    <div>Confirmer la suppression ?</div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="label-delete-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
