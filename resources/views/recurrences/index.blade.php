@extends('includes.layout')

@section('title')
    Récurrences
@endsection

@section('vite_imports')
    @vite(['resources/js/recurrences.js'])
@endsection

@section('content')
    <div>
        <a href="{{ route('recurrences_detect') }}" class="btn btn-primary">
            <i class="me-1 fas fa-arrows-rotate"></i> Détecter les récurrences
        </a>
    </div>

    <div id="recurrences-list-wrapper" class="mt-4">
        @include('recurrences.list')
    </div>

    @include('recurrences.modal_form')
@endsection
