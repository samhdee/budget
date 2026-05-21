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

        <div class="mt-5 mx-auto w-75">
            <div class="d-flex justify-content-end">
                <a href="{{ route('recurrences_detect') }}" class="btn btn-sm btn-primary">
                    <i class="me-1 fas fa-arrows-rotate"></i> Détecter les récurrences
                </a>
            </div>

            <div id="recurrences-list-wrapper" class="mt-3 list-wrapper">
                @include('recurrences.list')
            </div>
        </div>
    </div>

    @include('recurrences.modal_form')
@endsection
