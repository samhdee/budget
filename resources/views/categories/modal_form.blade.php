<div
    id="modal-categ-form"
    class="modal fade modal-form"
    tabindex="-1"
    aria-labelledby="categ-form-label"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categ-edit-form" class="mt-4" method="POST" action="{{ route('categ_store') }}">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="categ-form-label"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="categ-id" name="id"/>

                    <div class="mt-4 form-floating form-field">
                        <input
                            id="categ-appellation"
                            type="text"
                            name="appellation"
                            class="form-control"
                            maxlength="100"
                            autocomplete="off"
                            required
                        />
                        <label for="categ-appellation">Nom *</label>
                    </div>

                    <div class="mt-3 form-field">
                        <label for="categ-color">Jolie couleur</label>
                        <input id="categ-color" type="color" name="color" class="mt-1" required />
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <textarea id="categ-description" name="description" class="form-control" maxlength="255"></textarea>
                        <label for="categ-description">Description</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="categ-form-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
