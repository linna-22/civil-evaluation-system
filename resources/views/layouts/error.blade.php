<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>

        @yield('title', config('app.name'))

    </title>

    {{-- Favicon --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}">

    {{-- Vite --}}
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="font-body antialiased">

    {{-- Background --}}
    <div
        class="min-h-screen
               bg-gradient-to-br
               from-blue-700
               via-blue-600
               to-blue-800
               flex
               items-center
               justify-center
               px-4">

        @yield('content')

    </div>

</body>

</html>