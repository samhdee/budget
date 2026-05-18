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

        <main id="main-container" class="container">
            @csrf

            <div id="global-alert-wrapper">
                @if (Session()->has('message'))
                    <div class="alert alert-success alert-dismissible">
                        {{ Session::get('message') }}
                        <button type="button" class="btn btn-close"></button>
                    </div>
                @endif

                <div id="alert-message-wrapper"></div>
            </div>

            @yield('content')
        </main>
    </container>
</body>
