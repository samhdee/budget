<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Budget')</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])

    @yield('vite_imports')
</head>

<body>
    <Container>
        @include('includes.header')

        <main id="main-container" class="container">
            @if (Session()->has('message'))
                <div class="alert alert-success alert-dismissible">
                    {{ Session::get('message') }}
                    <button type="button" class="btn btn-close"></button>
                </div>
            @endif

            @yield('content')
        </main>
    </Container>
</body>
