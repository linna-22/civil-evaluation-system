<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Authentication')</title>
     {{-- Favicon --}}
    <link
        rel="icon"
        type="image/png"
        href="{{ asset('images/logo.png') }}">

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-slate-100">

    @yield('content')

</body>
</html>