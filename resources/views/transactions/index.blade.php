@php use App\Enums\TransactionType; @endphp

@extends('includes.layout')

@section('vite_imports')
    @vite(['resources/js/transactions.js'])
@endsection

@section('content')
    <div id="transactions-container">
        <h1>Transactions</h1>

        <div class="d-flex justify-content-between align-items-center">
            <div
                id="transactions-filter-wrapper"
                class="filters-wrapper d-flex gap-3"
                data-url="{{ route('transac_filter') }}"
                data-target="#transac-list-wrapper"
            >
                <div>
                    <select id="transac-filter-type" name="type" class="form-select">
                        <option value="">Tous</option>

                        @foreach(TransactionType::cases() as $transac_type)
                            <option value="{{ $transac_type->name }}">{{ $transac_type->value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="filter-wrapper with-reset">
                    <input id="transac-filter-benef" type="text" name="benef_name" class="form-control" size="30" />
                    <button type="button" class="filter-reset d-none btn btn-sm btn-close-white" data-target="#transac-filter-benef">
                        <i class="fas fa-xmark-circle"></i>
                    </button>
                </div>

                <div class="d-flex gap-1">
                    <input id="transac-filter-date-start" name="date_start" type="date" class="form-control" />
                    <input id="transac-filter-date-end" name="date_end" type="date" class="form-control" />
                </div>

                <div>
                    <button type="button" class="btn btn-sm btn-danger all-filter-reset">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>

            <div>
                <button
                    type="button"
                    class="btn btn-sm btn-success"
                    data-bs-toggle="modal"
                    data-bs-target="#modal_transac_form"
                    data-action="create"
                >
                    <i class="fas fa-plus-circle"></i> Créer
                </button>
            </div>
        </div>


        <div id="transac-list-wrapper" class="mt-5">
            @include('transactions.list')
        </div>

        @include('transactions.modal_form')
    </div>
@endsection
