<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>

        @yield('title') - {{ config('app.name') }}

    </title>

    <link rel="icon" href="{{ asset('images/circle_logo.png') }}">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

</head>

<body class="h-screen
           overflow-hidden
           bg-[#EDF2F7]
           font-body" data-success="{{ session('success') }}" data-error="{{ session('error') }}">

    {{-- Navbar --}}
    @include('partials.header')

    <div class="flex
               h-[calc(100vh-80px)]">

        {{-- Sidebar --}}
        @include('partials.sidebar')

        {{-- Content --}}
        <main class="flex-1
                   overflow-y-auto
                   p-5">

            @yield('content')

        </main>

    </div>

</body>

</html>