@php
    use Carbon\Carbon;
    use Carbon\CarbonPeriod;
@endphp

@extends('includes.layout')

@section('title')
    Dashboard
@endsection

@section('vite_imports')
    @vite(['resources/scss/dashboard.scss', 'resources/js/dashboard.js'])
@endsection

@section('content')
    <div id="dashboard-wrapper">
        <h1>Dashboard</h1>

        @php
            $now = Carbon::now();
            $period = CarbonPeriod::create(
                Carbon::parse($first_date->occurred_at)->startOfMonth()->format('Y-m-d'),
                '1 month',
                $now->format('Y-m-d')
            );
        @endphp

        <select id="transac-date-select" class="form-select" style="width: 130px">
            @foreach ($period as $month)
                <option
                    value="{{ $month->format('Y-m') }}"
                    @if ($month->format('m/Y') === $now->format('m/Y'))
                        selected="selected"
                    @endif
                >
                    {{ $month->format('m/Y') }}
                </option>
            @endforeach
        </select>

        <div id="transac-global-wrapper">
            @include('dashboard.lists')
        </div>
    </div>

    @include('transactions.modal_form')
    @include('transactions.modal_bulk_form')
    @include('transactions.modal_bulk_delete_form')
    @include('beneficiaries.modal_form')
@endsection
