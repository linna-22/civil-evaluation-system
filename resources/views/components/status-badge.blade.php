@props([
    'status' => 'active',
])

@php

$badges = [

    'active' => [
        'bg'   => 'bg-green-100',
        'text' => 'text-green-700',
        'label'=> 'សកម្ម',
    ],

    'inactive' => [
        'bg'   => 'bg-red-100',
        'text' => 'text-red-700',
        'label'=> 'អសកម្ម',
    ],

    'pending' => [
        'bg'   => 'bg-yellow-100',
        'text' => 'text-yellow-700',
        'label'=> 'រង់ចាំ',
    ],

];

$badge = $badges[$status] ?? $badges['active'];

@endphp

<span
    class="inline-flex
           items-center
           justify-center
           min-w-24
           h-9
           rounded-full
           text-sm
           font-medium
           {{ $badge['bg'] }}
           {{ $badge['text'] }}">

    {{ $badge['label'] }}

</span>