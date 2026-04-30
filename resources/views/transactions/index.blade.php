@php use App\Enums\TransactionType; @endphp

@extends('includes.layout')

@section('vite_imports')
    @vite([''])
@endsection

@section('content')
    <div id="transactions-container">
        <h1>Transactions</h1>

        <div class='mx-auto w-75 d-flex justify-content-end'>
            <a class="btn btn-sm btn-success" href="{{ route('transac_form') }}">
                <i class="fas fa-plus-circle me-1"></i> Créer
            </a>
        </div>

        <div id="transactions-filter-wrapper" class="d-flex gap-3">
            <div>
                <select id="transac-filter-type" class="form-select">
                    <option>Tous</option>

                    @foreach(TransactionType::cases() as $transac_type)
                        <option value="{{ $transac_type->name }}">{{ $transac_type->value }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        @include('transactions.list')
    </div>
@endsection
