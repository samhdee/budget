@extends('includes.layout')

@section('title')
    Récurrences
@endsection

@section('vite_imports')
    @vite(['resources/js/recurrences.js'])
@endsection

@section('content')
    <div id="recurrences-wrapper">
        <h1>Récurrences</h1>

        <div class="mt-4 d-flex justify-content-end">
            <a
                href="{{ route('recurrences_store') }}"
                class="btn btn-sm btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modal-recurrence-form"
                data-action="create"
            >
                <i class="me-1 fas fa-plus-circle"></i> Ajouter
            </a>

            <a href="{{ route('recurrences_detect') }}" class="ms-2 btn btn-sm btn-primary">
                <i class="me-1 fas fa-arrows-rotate"></i> Détecter
            </a>
        </div>

        <div id="recurrences-list-wrapper" class="mt-4 list-wrapper">
            @include('recurrences.lists')
        </div>
    </div>

    @include('recurrences.modal_form')
    @include('recurrences.modal_bulk_deactivate_form')
    @include('recurrences.modal_link_transac')
    @include('beneficiaries.modal_form')
@endsection
