@extends('includes.layout')

@section('title') Transactions @endsection

@section('vite_imports')
    @vite(['resources/js/transactions.js'])
@endsection

@section('content')
    <div id="transactions-container">
        <h1>Transactions</h1>

        @include('transactions.filters')

        <div id="transac-list-wrapper" class="mt-5 list-wrapper">
            @include('transactions.list')
        </div>
    </div>
@endsection
