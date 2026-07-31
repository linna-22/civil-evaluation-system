@props([
    'status',
])

@php

    $statuses = [

        'pending' => [
            'text' => 'មិនទាន់ចាប់ផ្តើម',
            'class' => 'bg-amber-100 text-amber-700',
            'icon' => 'circle-alert',
        ],

        'draft' => [
            'text' => 'កំពុងបំពេញ',
            'class' => 'bg-blue-100 text-blue-700',
            'icon' => 'file-pen-line',
        ],

        'submitted' => [
            'text' => 'បានបញ្ជូន',
            'class' => 'bg-green-100 text-green-700',
            'icon' => 'badge-check',
        ],

        'approved' => [
            'text' => 'បានអនុម័ត',
            'class' => 'bg-emerald-100 text-emerald-700',
            'icon' => 'circle-check-big',
        ],

        'rejected' => [
            'text' => 'ត្រូវបានបដិសេធ',
            'class' => 'bg-red-100 text-red-700',
            'icon' => 'circle-x',
        ],

    ];

    $badge = $statuses[$status] ?? $statuses['pending'];

@endphp

<span
    class="inline-flex
           items-center
           gap-2
           rounded-full
           px-3
           py-1.5
           text-sm
           font-medium
           {{ $badge['class'] }}">

    <i
        data-lucide="{{ $badge['icon'] }}"
        class="w-4 h-4">
    </i>

    {{ $badge['text'] }}

</span>