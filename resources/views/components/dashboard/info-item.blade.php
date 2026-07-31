@props([
    'label',
    'value',
])

<div class="flex items-start justify-between gap-4 py-3">

    <span class="text-sm text-gray-500">
        {{ $label }}
    </span>

    <span class="text-sm font-semibold text-gray-800 text-right">
        {{ $value }}
    </span>

</div>