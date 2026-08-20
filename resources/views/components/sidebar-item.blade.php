@props([
    'icon',
    'title',
    'route' => null,
    'url' => '#',
])

@php

    $active = $route
        ? request()->routeIs($route)
        : false;

@endphp

<a
    href="{{ $url }}"
    class="
        sidebar-item
        flex
        items-center
        gap-4
        h-12
        mx-4
        px-4
        rounded-2xl
        transition-all
        duration-300

        {{ $active
    ? 'bg-white text-blue-600 shadow-md'
    : 'text-white hover:bg-white/10' }}
    ">

    <i
 data-lucide="{{ $icon }}"
        class="w-6 h-6 shrink-0">
    </i>

    <span
        class="
            sidebar-text
            font-body
            text-base">

        {{ $title }}

    </span>

</a>