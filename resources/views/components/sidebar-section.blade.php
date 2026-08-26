@props([
    'title',
])

<div class="mt-6">

    <p
    class="
        sidebar-section-title
        sidebar-text
        font-body
        px-6
        mb-2
        text-sm
        font-medium
        text-blue-200">

        {{ $title }}

    </p>

    {{ $slot }}

</div>