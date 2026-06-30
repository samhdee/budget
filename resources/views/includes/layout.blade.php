<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Budget')</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @yield('vite_imports')
</head>

<body>
    <container>
        @include('includes.header')

        <main id="main-container" class="container-xxl">
            @csrf

            <div id="global-alert-wrapper">
                @if (Session()->has('message'))
                    <div class="alert alert-success alert-dismissible">
                        {{ Session::get('message') }}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (Session()->has('error'))
                    <div class="alert alert-danger alert-dismissible">
                        {!! Session::get('error') !!}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if (Session()->has('warning'))
                    <div class="alert alert-warning alert-dismissible">
                        {{ Session::get('warning') }}
                        <button type="button" class="btn btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <div id="alert-message-wrapper"></div>
            </div>

            @yield('content')
        </main>
    </container>
</body>
