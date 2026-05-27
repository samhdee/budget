@php
    use App\Enums\RecurrenceFreqUnit;
    use App\Models\Beneficiary;
@endphp

<div
    id="modal-recur-bulk-toggle-active-form"
    class="modal fade modal-bulk-form"
    tabindex="-1"
    aria-labelledby="recur-bulk-toggle-active-form-title"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="recur-bulk-toggle-active-form-title" class="modal-title fs-4">Désactiver</h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form
                    id="recurrence-edit-form"
                    method="POST"
                    action="{{ route('recurrences_bulk_toggle_active') }}"
                    data-list="#recurrences-list-wrapper"
                >
                    <div class="d-none alert alert-danger"></div>

                    <p>Toggle l’activation de ces récurrences ?</p>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="recurrence-form-submit" type="submit" class="btn btn-success">Envoyer</button>
            </div>
        </div>
    </div>
</div>
