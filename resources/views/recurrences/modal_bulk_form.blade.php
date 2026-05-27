@php
    use App\Enums\RecurrenceFreqUnit;
    use App\Models\Beneficiary;
@endphp

<div
    id="modal-recurrence-form"
    class="modal fade modal-bulk-form"
    tabindex="-1"
    aria-labelledby="recurrence-bulk-form-title"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="recurrence-bulk-form-title" class="modal-title fs-4"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="recurrence-bulk-edit-form" method="POST" action="{{ route('recurrences_store') }}">
                    <div class="d-none alert alert-danger"></div>

                    <div class="form-floating form-field">
                        <input id="recurrence-label" type="text" name="label" class="form-control"/>
                        <label for="recurrence-label">Label</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <input id="recurrence-ends-at" type="date" name="ends_at" class="form-control" required/>
                        <label for="recurrence-ends-at">Date de fin</label>
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
