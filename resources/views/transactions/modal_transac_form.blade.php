@php
    use App\Enums\TransactionType;
    use App\Models\Beneficiary;
@endphp

<div class="modal fade" id="modal-transac-form" tabindex="-1" aria-labelledby="transac_form_label" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="transac-edit-form" class="mt-4" method="POST" action="{{ route('transac_store') }}">
                <div class="modal-header">
                    <h3 class="modal-title fs-4" id="transac_form_label"></h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <input type="hidden" id="transac-id" name="id"/>

                    <div class="mt-4 form-floating">
                        <input id="transac-occurred-at" type="date" name="occurred_at" class="form-control" required />
                        <label for="transac-occurred-at">Date</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <select id="transac-form-type" name="type" class="form-select">
                            <option value="">Tous</option>

                            @foreach(TransactionType::cases() as $transac_type)
                                <option value="{{ $transac_type->name }}">
                                    {{ getTransactionTypeLabel($transac_type->name) }}
                                </option>
                            @endforeach
                        </select>

                        <label for="transac-form-type">Type</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <input id="transac-amount" type="number" name="amount" class="form-control" step=".01" required />
                        <label for="transac-amount">Montant</label>
                    </div>

                    <div class="mt-3 form-floating">
                        <textarea id="transac-notes" name="notes" class="form-control"></textarea>
                        <label for="transac-notes">Notes</label>
                    </div>

                    <div class="mt-3 position-relative">
                        <div class="pe-5 form-floating">
                            <select id="transac-benef-id" name="beneficiary_id" class="form-select" required>
                                <option value=""></option>

                                @php /** @var Beneficiary[] $beneficiaries */ @endphp
                                @foreach ($beneficiaries as $beneficiary)
                                    <option value="{{ $beneficiary->id }}" data-pretty_name="{{ $beneficiary->pretty_name }}">
                                        {{ $beneficiary->raw_name }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="transac-benef-id">Bénéficiaire</label>
                        </div>

                        <div class="position-absolute top-25 end-0">
                            <button
                                type="button"
                                class="btn btn-sm btn-success"
                                data-bs-toggle="collapse"
                                data-bs-target="#transac-new-benef-wrapper"
                                aria-expanded="false"
                                aria-controls="transac-new-benef-wrapper"
                            >
                                <i class="fas fa-plus-circle"></i>
                            </button>
                        </div>
                    </div>

                    <div id="transac-new-benef-wrapper" class="mt-3 collapse form-floating">
                        <input id="transac-new-benef" type="text" name="new_benef" class="form-control" />
                        <label for="transac-new-benef">Nouveau bénéficiaire</label>
                    </div>

                    <div id="transac-file-wrapper" class="mt-3 form-floating">
                        <input id="transac-file" type="text" name="file" class="form-control" disabled />
                        <label for="transac-file">Fichier</label>
                    </div>

                    <div id="transac-line-wrapper" class="mt-3 form-floating">
                        <input id="transac-line" type="number" name="line" class="form-control" disabled />
                        <label for="transac-line">Ligne</label>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button id="transac-form-submit" type="submit" class="btn btn-success">Envoyer</button>
                </div>
            </form>
        </div>
    </div>
</div>
