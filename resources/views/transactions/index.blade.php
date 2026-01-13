@extends('includes.layout')

@section('content')
    <div id="transactions-container">
        <h1>Transactions</h1>

        <div class='mx-auto w-75 d-flex justify-content-end'>
            <a class="btn btn-sm btn-success" href="{{ route('transac_form') }}">
                <i class="fas fa-plus-circle me-1" /> Créer
            </a>
        </div>

        <table class="mt-3 mx-auto w-75 table-striped table-bordered">
            <thead>
                <tr>
                    <th style="width: 12rem">Montant</th>
                    <th>Date</th>
                    <th style="width: 4rem"></th>
                </tr>
            </thead>

            <tbody>
                @forelse ($transactions as $transaction)
                    <tr>
                        <td>{{ $transaction->amount }}</td>
                        <td>{{ $transaction->occurred_at }}</td>

                        <td class="text-center">
                            <a
                                class="btn btn-sm btn-primary"
                                href="{{ route('transac_form', ['transac_id' => $transaction->id]) }}"
                            >
                                <i class="fas fa-pencil" />
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-muted fst-italic">
                            <i class="fa-solid fa-ban me-1"></i> Aucun résultat
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
