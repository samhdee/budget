@extends('includes.layout')

@section('content')
    <div id="label-form-container">
        <h1>
            {{ !empty($label) ? "Modifier {$label->name}" : 'Ajouter un label' }}
        </h1>

        <div class="w-50 mt-5 mx-auto">
            <form id="form-label">
                <input type="hidden" name="id" value="{{ !empty($label) ? $label->id : '' }}" />

                <div class="form-control form-floating">
                    <input
                        id="label-name"
                        type="text"
                        name="name"
                        @if (!empty($label))
                            value="{{ $label->name }}"
                        @endif
                        maxlength="100"
                        autocomplete="off"
                        required
                    />

                    <label for="label-name">Nom</label>
                </div>

                <div class="form-control form-floating">
                    <textarea
                        id="label-description"
                        name="description"
                        maxlength="255"
                        rows="6"
                    >@if (!empty($label)) {{ $label->description }}" @endif</textarea>

                    <label for="label-description">Description</label>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="me-2 btn btn-success">Sauvegarder</button>
                    <a href="{{ route('labels_index') }}" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
@endsection
