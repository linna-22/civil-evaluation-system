@props([
    'icon' => null,
    'href' => null,
    'variant' => 'primary',
])

@php

$variants = [

    'primary' => 'bg-blue-500 hover:bg-blue-600 text-white',

    'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-700',

    'success' => 'bg-green-600 hover:bg-green-700 text-white',

    'danger' => 'bg-red-600 hover:bg-red-700 text-white',

    'warning' => 'bg-orange-500 hover:bg-orange-600 text-white',

];

$classes = $variants[$variant] ?? $variants['primary'];

@endphp

@if ($href)

    <a
        href="{{ $href }}"
        {{ $attributes->merge([
            'class' => "inline-flex
                        items-center
                        gap-2
                        h-11
                        px-4
                        rounded-xl
                        font-body
                        text-sm
                        font-medium
                        transition
                        shadow-sm
                        $classes"
        ]) }}>

        @if($icon)

            <i
                data-lucide="{{ $icon }}"
                class="w-4 h-4">
            </i>

        @endif

        {{ $slot }}

    </a>

@else

    <button
        {{ $attributes->merge([
            'class' => "inline-flex
                        items-center
                        gap-2
                        h-11
                        px-5
                        rounded-xl
                        font-body
                        text-sm
                        font-medium
                        transition
                        shadow-sm
                        cursor-pointer
                        $classes"
        ]) }}>

        @if($icon)

            <i
                data-lucide="{{ $icon }}"
                class="w-4 h-4">
            </i>

        @endif

        {{ $slot }}

    </button>

@endif