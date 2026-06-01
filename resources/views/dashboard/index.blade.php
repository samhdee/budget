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
                    value="{{ $month->format('m/Y') }}"
                    @if ($month->format('m/Y') === $now->format('m/Y'))
                        selected="selected"
                    @endif
                >
                    {{ $month->format('m/Y') }}
                </option>
            @endforeach
        </select>

        <ul id="transactions-tabs" class="mt-4 nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link active"
                    id="general-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#general-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="general-tab-pane"
                    aria-selected="true"
                >
                    Vue d'ensemble
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="transac-expanses-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#transac-expanses-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="transac-expanses-tab-pane"
                    aria-selected="false"
                >
                    Dépenses
                </button>
            </li>

            <li class="nav-item" role="presentation">
                <button
                    class="nav-link"
                    id="transac-revenus-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#transac-revenus-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="transac-revenus-tab-pane"
                    aria-selected="false"
                >
                    Revenus
                </button>
            </li>
        </ul>

        <div id="recurrences-tab-content" class="tab-content">
            <div
                id="general-tab-pane"
                class="tab-pane fade show active container"
                role="tabpanel"
                aria-labelledby="general-tab"
                tabindex="0"
            >
                <div id="general-wrapper" class="mt-5 list-wrapper">
                    @include('dashboard.general')
                </div>
            </div>

            <div
                id="transac-expanses-tab-pane"
                class="tab-pane fade container"
                role="tabpanel"
                aria-labelledby="transac-expanses-tab"
                tabindex="0"
            >
                <div class="mt-5">
                    @include('dashboard.expanses-filters')
                </div>

                <div id="expanses-list-wrapper" class="mt-4 list-wrapper">
                    @include('dashboard.expanses-list')
                </div>
            </div>

            <div
                id="transac-revenus-tab-pane"
                class="tab-pane fade container"
                role="tabpanel"
                aria-labelledby="transac-revenus-tab"
                tabindex="0"
            >
                <div class="mt-4 list-wrapper">
                    @include('dashboard.revenus-list')
                </div>
            </div>
        </div>
    </div>

    @include('transactions.modal_form')
    @include('beneficiaries.modal_form')
@endsection
