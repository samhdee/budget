@php
    use App\Enums\TransactionType;
    use App\Models\Beneficiary;
    use App\Models\Category;
    use App\Models\Label;
@endphp

<div class="modal fade modal-form" id="modal-transac-form" aria-labelledby="transac-form-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="transac-form-title" class="modal-title fs-4"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="transac-edit-form" method="POST" action="{{ route('transac_store') }}">
                    <div class="d-none alert alert-danger"></div>

                    <input type="hidden" id="transac-id" name="id"/>

                    <div class="form-floating form-field">
                        <input id="transac-occurred-at" type="date" name="occurred_at" class="form-control" required/>
                        <label for="transac-occurred-at">Date</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <select id="transac-form-type" name="type" class="form-select" required>
                            <option value=""></option>

                            @foreach(TransactionType::cases() as $transac_type)
                                <option value="{{ $transac_type->name }}">
                                    {{ getTransactionTypeLabel($transac_type->name) }}
                                </option>
                            @endforeach
                        </select>

                        <label for="transac-form-type">Type</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <input
                            id="transac-amount"
                            type="number"
                            name="amount"
                            class="form-control"
                            step=".01"
                           required
                        />
                        <label for="transac-amount">Montant</label>
                    </div>

                    <div class="mt-3 position-relative">
                        <div class="pe-5 form-floating form-field">
                            <select id="transac-benef-id" name="beneficiary_id" class="form-select select2"
                                    style="width: 100%;">
                                <option value=""></option>

                                @php /** @var Beneficiary[] $beneficiaries */ @endphp
                                @foreach ($beneficiaries as $beneficiary)
                                    <option value="{{ $beneficiary->id }}">
                                        {{ !empty($beneficiary->pretty_name)
                                            ? "{$beneficiary->pretty_name} ({$beneficiary->raw_name})"
                                            : $beneficiary->raw_name
                                        }}
                                    </option>
                                @endforeach
                            </select>

                            <label for="transac-benef-id">Bénéficiaire</label>

                            {{--                            <div class="position-absolute top-25 end-0">--}}
                            {{--                                <button--}}
                            {{--                                    type="button"--}}
                            {{--                                    class="btn btn-sm btn-success"--}}
                            {{--                                    data-bs-toggle="collapse"--}}
                            {{--                                    data-bs-target="#transac-new-benef-wrapper"--}}
                            {{--                                    aria-expanded="false"--}}
                            {{--                                    aria-controls="transac-new-benef-wrapper"--}}
                            {{--                                >--}}
                            {{--                                    <i class="fas fa-plus-circle"></i>--}}
                            {{--                                </button>--}}
                            {{--                            </div>--}}
                        </div>

                        <div id="transac-new-benef-wrapper" class="mt-3 collapse form-floating form-field">
                            <input id="transac-new-benef" type="text" name="new_benef" class="form-control"/>
                            <label for="transac-new-benef">Nouveau bénéficiaire</label>
                        </div>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <select id="transac-category-id" name="category_id" class="form-select">
                            <option value=""></option>

                            @php /** @var Category[] $category */ @endphp
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->appellation }}
                                </option>
                            @endforeach
                        </select>

                        <label for="transac-category-id">Catégorie</label>
                    </div>

                    <div class="mt-3 form-field">
                        <label for="transac-label-ids">Labels</label>

                        <select id="transac-label-ids" name="labels" class="select2 form-select" style="width: 100%;" multiple>
                            <option value=""></option>

                            @php /** @var Label[] $label */ @endphp
                            @foreach ($labels as $label)
                                <option value="{{ $label->id }}">
                                    {{ $label->appellation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <textarea id="transac-notes" name="notes" class="form-control" maxlength="255"></textarea>
                        <label for="transac-notes">Notes</label>
                    </div>

                    <div id="transac-file-wrapper" class="mt-3 form-floating form-field">
                        <input id="transac-file" type="text" name="file" class="form-control" disabled/>
                        <label for="transac-file">Fichier</label>
                    </div>

                    <div id="transac-line-wrapper" class="mt-3 form-floating form-field">
                        <input id="transac-line" type="number" name="line" class="form-control" disabled/>
                        <label for="transac-line">Ligne</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="transac-form-submit" type="submit" class="btn btn-success">Envoyer</button>
            </div>
        </div>
    </div>
</div>
