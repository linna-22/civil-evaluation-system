@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
])

@php

$colors = [

    'blue' => [
        'bg'   => 'bg-blue-100',
        'text' => 'text-blue-600',
    ],

    'green' => [
        'bg'   => 'bg-green-100',
        'text' => 'text-green-600',
    ],

    'orange' => [
        'bg'   => 'bg-orange-100',
        'text' => 'text-orange-600',
    ],

    'purple' => [
        'bg'   => 'bg-purple-100',
        'text' => 'text-purple-600',
    ],

];

$style = $colors[$color] ?? $colors['blue'];

@endphp

<div
    class="bg-white
           rounded-3xl
           p-6
           shadow-sm
           border
           border-gray-100
           hover:shadow-md
           transition-all
           duration-300">

    <div class="flex items-center justify-between">

        <div>

            <p
                class="font-body
                       text-gray-500
                       text-sm">

                {{ $title }}

            </p>

            <h2
                class="mt-2
                       text-4xl
                       font-bold
                       text-gray-800">

                {{ $value }}

            </h2>

        </div>

        <div
            class="w-16
                   h-16
                   rounded-2xl
                   {{ $style['bg'] }}
                   flex
                   items-center
                   justify-center">

            <i
                data-lucide="{{ $icon }}"
                class="w-8 h-8 {{ $style['text'] }}">
            </i>

        </div>

    </div>

</div>