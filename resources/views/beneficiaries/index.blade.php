@extends('includes.layout')

@section('title') Bénéficiaires @endsection

@section('vite_imports')
    @vite(['resources/js/beneficiaries.js'])
@endsection

@section('content')
    <div id="beneficiaires-wrapper">
        <h1>Bénéficiaires</h1>

        @include('beneficiaries.filters')

        <div id="benef-list-wrapper" class="mt-4 list-wrapper">
            @include('beneficiaries.list')
        </div>
    </div>

    @include('beneficiaries.modal_form')
    @include('beneficiaries.modal_bulk_form')
    @include('beneficiaries.modal_bulk_sync')
@endsection
