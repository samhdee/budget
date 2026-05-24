@extends('includes.layout')

@section('title')
    Dashboard
@endsection

@section('vite_imports')
    @vite(['resources/js/dashboard.js'])
@endsection

@section('content')
    <div id="dashboard-wrapper">
        <h1>Dashboard</h1>

        <ul id="transactions-tabs" class="mt-3 nav nav-tabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button
                    class="nav-link active"
                    id="transac-expanses-tab"
                    data-bs-toggle="tab"
                    data-bs-target="#transac-expanses-tab-pane"
                    type="button"
                    role="tab"
                    aria-controls="transac-expanses-tab-pane"
                    aria-selected="true"
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
                id="transac-expanses-tab-pane"
                class="tab-pane fade show active container"
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

        @include('transactions.modal_form')
    </div>
@endsection
