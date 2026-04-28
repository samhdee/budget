@php use App\Models\BgaUser; @endphp
@extends('includes.layout')

@section('title') Créer un compte @endsection

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center">Créer un compte</h2>

                <p class="mt-4">Déjà un compte ? <a href="{{ route('login') }}">Se connecter</a></p>

                <div class="mt-4">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="reg-name" class="col-md-4 col-form-label text-md-end">Nom *</label>

                            <div class="col-md-6">
                                <input
                                    id="reg-name"
                                    type="text"
                                    class="form-control @error('name') is-invalid @enderror"
                                    name="name"
                                    value="{{ old('name') }}"
                                    autocomplete="name"
                                />

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="reg-email" class="col-md-4 col-form-label text-md-end">
                                Adresse mail *
                            </label>

                            <div class="col-md-6">
                                <input
                                    id="reg-email"
                                    type="email"
                                    class="form-control @error('email') is-invalid @enderror"
                                    name="email"
                                    value="{{ old('email') }}"
                                    required
                                    autocomplete="email"
                                />

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="reg-password" class="col-md-4 col-form-label text-md-end">
                                Mot de passe *
                            </label>

                            <div class="col-md-6">
                                <input
                                    id="reg-password"
                                    type="password"
                                    class="form-control @error('password') is-invalid @enderror"
                                    name="password"
                                    required
                                    autocomplete="new-password"
                                />

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="reg-password-confirm" class="col-md-4 col-form-label text-md-end">
                                Confirmer le mot de passe *
                            </label>

                            <div class="col-md-6">
                                <input
                                    id="reg-password-confirm"
                                    type="password"
                                    class="form-control"
                                    name="password_confirmation"
                                    required
                                    autocomplete="new-password"
                                />
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">Valider</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
