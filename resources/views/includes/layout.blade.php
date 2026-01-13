<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title', 'Budget')</title>

    @vite(['resources/scss/app.scss', 'resources/js/app.js'])
</head>

<body>
    <Container>
        @include('includes.header')

        <main id="main-container" class="container">
            @yield('content')
        </main>
    </Container>
</body>