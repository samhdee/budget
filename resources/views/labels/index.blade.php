@extends('includes.layout')

@section('title') Étiquettes @endsection

@section('vite_imports')
    @vite(['resources/js/labels.js'])
@endsection

@section('content')
    <div id="labels-wrapper">
        <h1>Labels</h1>

        <div class="d-flex justify-content-end mx-auto w-75">
            <button
                type="button"
                class="btn btn-sm btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modal-label-form"
                data-action="create"
                data-type="étiquette"
            >
                <i class="me-1 fas fa-plus-circle"></i> Créer
            </button>
        </div>

        <div id="labels-list-wrapper" class="list-wrapper">
            @include('labels.list')
        </div>
    </div>

    @include('labels.modal_form')
    @include('labels.modal_delete')
@endsection
