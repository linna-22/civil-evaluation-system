@props([
    'code',
    'icon',
    'title',
    'description',
    'button',
    'url',
])

<x-card class="w-1/2 max-w-lg p-10 text-center">

    {{-- Logo --}}
    <div class="flex justify-center mb-6">

        <img
            src="{{ asset('images/logo.png') }}"
            alt="Logo"
            class="w-20 h-20 object-contain">

    </div>

    {{-- Error Code --}}
    <h1
        class="text-4xl
               font-bold
               text-red-600">

        {{ $code }}

    </h1>

    {{-- Title --}}
    <h2
        class="font-title
               text-2xl
               text-blue-600
               mt-4
               leading-relaxed">

        {{ $title }}

    </h2>

    {{-- Description --}}
    <p
        class="font-body
               text-gray-500
               mt-5
               mb-5
               leading-8">

        {{ $description }}

    </p>

    {{-- Button --}}
    <div class="mt-10">

        <a
            href="{{ $url }}"
            class="inline-flex
                items-center
                justify-center
                px-8
                h-10
                rounded-2xl
                bg-blue-600
                hover:bg-blue-700
                text-white
                transition">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-6a2 2 0 0 1 2.582 0l7 6A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>

            {{ $button }}

        </a>

    </div>

</x-card>