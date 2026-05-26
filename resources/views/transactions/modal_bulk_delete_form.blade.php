@php
    use App\Enums\TransactionType;
    use App\Models\Beneficiary;
    use App\Models\Category;
@endphp

<div class="modal fade" id="modal-transac-bulk-form" aria-labelledby="transac-bulk-form-title" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h3 id="transac-bulk-form-title" class="modal-title fs-4"></h3>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <div class="modal-body">
                <form id="transac-bulk-edit-form" method="POST" action="{{ route('transac_store') }}">
                    <div class="d-none alert alert-danger"></div>

                    <div class="mt-3 form-floating form-field">
                        <select id="transac-bulk-form-type" name="type" class="form-select" required>
                            <option value=""></option>

                            @foreach(TransactionType::cases() as $transac_type)
                                <option value="{{ $transac_type->name }}">
                                    {{ getTransactionTypeLabel($transac_type->name) }}
                                </option>
                            @endforeach
                        </select>

                        <label for="transac-bulk-form-type">Type</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <select id="transac-bulk-benef-id" name="beneficiary_id" class="select2 form-select" style="width: 100%;">
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

                        <label for="transac-bulk-benef-id">Bénéficiaire</label>
                    </div>

                    <div class="mt-3 form-floating form-field">
                        <select id="transac-bulk-category-id" name="category_id" class="form-select" required>
                            <option value=""></option>

                            @php /** @var Category[] $category */ @endphp
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}">
                                    {{ $category->appellation }}
                                </option>
                            @endforeach
                        </select>

                        <label for="transac-bulk-category-id">Catégorie</label>
                    </div>
                </form>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <button id="transac-bulk-form-submit" type="submit" class="btn btn-success">Envoyer</button>
            </div>
        </div>
    </div>
</div>
