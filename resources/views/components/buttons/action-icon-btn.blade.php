@props([
    'icon',
    'url' => '#',
    'color' => 'blue',
])

@php

$colors = [

    'blue' => 'bg-blue-100 text-blue-600 hover:bg-blue-200',

    'green' => 'bg-green-100 text-green-600 hover:bg-green-200',

    'amber' => 'bg-amber-100 text-amber-600 hover:bg-amber-200',

    'red' => 'bg-red-100 text-red-600 hover:bg-red-200',

];

@endphp

<a
    href="{{ $url }}"

    {{ $attributes->merge([

        'class' => "
            inline-flex
            items-center
            justify-center
            w-9
            h-9
            rounded-xl
            transition
            {$colors[$color]}
        "

    ]) }}>

    <i
        data-lucide="{{ $icon }}"
        class="w-4 h-4">
    </i>

</a>