<div class="modal fade modal-form" id="modal-benef-form" tabindex="-1" aria-labelledby="benef-form-label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="benef-edit-form" class="mt-4" method="POST" action="{{ route('benef_store') }}">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="benef-form-label"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="benef-id" name="id"/>

                    <div class="mt-4 form-floating">
                        <input id="benef-occurred-at" type="text" name="raw_name" class="form-control" disabled />
                        <label for="benef-occurred-at">Label brut</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <input id="benef-pretty_name" type="text" name="pretty_name" class="form-control" />
                        <label for="benef-pretty_name">Label joli</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <textarea id="benef-notes" name="notes" class="form-control" maxlength="255"></textarea>
                        <label for="benef-notes">Notes</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="benef-form-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
