@props([
    'title',
    'url' => null,
])

@if($url)

    <a
        href="{{ $url }}"
        class="hover:text-primary transition">

        {{ $title }}

    </a>

@else

    <span
        class="text-gray-800">

        {{ $title }}

    </span>

@endif