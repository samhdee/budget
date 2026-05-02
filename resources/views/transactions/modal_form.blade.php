@php use App\Models\Beneficiary; @endphp

<div class="modal fade" id="modal_transac_form" tabindex="-1" aria-labelledby="transac_form_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="transac-edit-form" class="mt-4" action="{{ route('transac_store') }}">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="transac_form_label"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="transac-id" name="id"/>

                    <div class="form-floating">
                        <input id="transac-occurred-at" type="date" name="occurred_at" class="form-control" required />
                        <label for="transac-occurred-at">Date</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <input id="transac-amount" type="number" name="amount" class="form-control" required/>
                        <label for="transac-amount">Montant</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <select id="transac-benef-id" name="benef_id" class="form-select" required>
                            <option value=""></option>

                            @php /** @var Beneficiary[] $beneficiaries */ @endphp
                            @foreach ($beneficiaries as $beneficiary)
                                <option value="{{ $beneficiary->id }}">
                                    @if (!empty($beneficiary->pretty_name))
                                        {{ $beneficiary->pretty_name }} ({{ $beneficiary->raw_name }})
                                    @else
                                        {{ $beneficiary->raw_name }}
                                    @endif
                                </option>
                            @endforeach
                        </select>

                        <label for="transac-benef-id">Bénéficiaire</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <input id="transac-file" type="text" name="file" class="form-control" disabled />
                        <label for="transac-file">Fichier</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <input id="transac-line" type="number" name="line" class="form-control" disabled />
                        <label for="transac-line">Ligne</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="transac-form-submit" type="button" class="btn btn-success">Valider</button>
                </div>
            </form>
        </div>
    </div>
</div>
