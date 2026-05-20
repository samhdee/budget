@php
    use App\Models\Beneficiary;
@endphp

<div
    id="modal-recurrence-form"
    class="modal fade modal-form"
    tabindex="-1"
    aria-labelledby="recurrence-form-title"
    aria-hidden="true"
>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="recurrence-form-title" class="modal-title fs-4"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="recurrence-edit-form" method="POST" action="{{ route('recurrences_store') }}">
                    <div class="d-none alert alert-danger"></div>

                    <input type="hidden" id="recurrence-id" name="id"/>

                    <div class="form-floating form-field">
                        <input id="recurrence-label" type="text" name="label" class="form-control" />
                        <label for="recurrence-label">Label</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <input
                            id="recurrence-amount"
                            type="number"
                            name="amount"
                            class="form-control"
                            step=".01"
                           required
                        />
                        <label for="recurrence-amount">Montant *</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <select id="recurrence-benef-id" name="beneficiary_id" class="form-select" required>
                            <option value=""></option>

                            @php /** @var Beneficiary[] $beneficiaries */ @endphp
                            @foreach ($beneficiaries as $beneficiary)
                                <option value="{{ $beneficiary->id }}">
                                    {{ !empty($beneficiary->pretty_name)
                                        ? $beneficiary->pretty_name :
                                        $beneficiary->raw_name
                                    }}
                                </option>
                            @endforeach
                        </select>

                        <label for="recurrence-benef-id">Bénéficiaire *</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <input id="transac-occurred-at" type="date" name="occurred_at" class="form-control" required/>
                        <label for="transac-occurred-at">Date de fin</label>
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
