@props([
    'type',
    'href' => '#',
])

@php

$buttons = [

    'view' => [

        'icon' => 'eye',

        'class' => 'bg-blue-100 text-blue-600 hover:bg-blue-200',

    ],

    'edit' => [

        'icon' => 'square-pen',

        'class' => 'bg-yellow-100 text-yellow-600 hover:bg-yellow-200',

    ],

    'delete' => [

        'icon' => 'trash-2',

        'class' => 'bg-red-100 text-red-600 hover:bg-red-200',

    ],

];

$button = $buttons[$type];

@endphp

<a

    href="{{ $href }}"

    class="inline-flex
           items-center
           justify-center
           w-10
           h-10
           rounded-xl
           transition
           {{ $button['class'] }}">

    <i
        data-lucide="{{ $button['icon'] }}"
        class="w-4 h-4">
    </i>

</a>