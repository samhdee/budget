@extends('includes.layout')

@section('vite_imports')
    @vite(['resources/js/categories.js', 'resources/js/helpers/forms.js'])
@endsection

@section('content')
    <div id="categories-wrapper">
        <h1>Coucou c&rsquo;est les catégoRIRES</h1>

        <div class="mt-5 mx-auto w-75 d-flex justify-content-end">
            <button
                type="button"
                class="btn btn-sm btn-success"
                data-bs-toggle="modal"
                data-bs-target="#modal-categ-form"
                data-action="create"
                data-type="catégorie"
            >
                <i class="me-1 fas fa-plus-circle"></i> Créer
            </button>
        </div>

        <div id="categories-list-wrapper">
            @include('categories.list')
        </div>
    </div>

    @include('categories.modal_form')
    @include('categories.modal_delete')
@endsection
