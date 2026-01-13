<div id="transactions-form-wrapper">
    <h1>{{ !empty($transaction) ? 'Modifier' : 'Ajouter' }}</h1>

    <div class="w-50 mt-5 mx-auto">
        <form id="transaction-form">
            <input type="hidden" name="id" value={{ !empty($transaction) ? $transaction->id : '' }} />

            <div class="form-control form-floating">
                <label for="transac-amount">Montant</label>

                <input
                    id="transac-amount"
                    type="number"
                    name="amount"
                    value="{{ $transaction->amount }}"
                    maxlength="100"
                    autocomplete="off"
                    data-protonpass-ignore="true"
                    required
                />
            </div>

            <div class="form-control form-floating">
                <label for="transac-occurred-at">Date</label>

                <input
                    id="transac-occurred-at"
                    type="date"
                    name="occurred_at"
                    value="{{ $transaction->occurred_at }}"
                    maxlength="255"
                />
            </div>

            <div class="d-flex justify-content-between">
                <button role="button" class="me-2 btn btn-success">Sauvegarder</button>
                <a href="{{ route('transac_index') }}" class="btn btn-secondary">Annuler</a>
            </div>
        </form>
    </div>
</div>
