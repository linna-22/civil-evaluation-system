@props([
    'title',
    'value',
    'icon',
    'iconBg' => 'bg-primary/10',
    'iconColor' => 'text-primary',
])

<div
    class="rounded-xl
           border
           border-gray-200
           p-5
           h-full
           transition
           hover:shadow-md">

    <div class="flex items-center justify-between">

        <div>

            <p class="text-sm text-gray-500">
                {{ $title }}
            </p>

            <h3 class="mt-2 text-lg font-semibold text-gray-800">
                {{ $value }}
            </h3>

        </div>

        <div
            class="w-12
                   h-12
                   rounded-xl
                   {{ $iconBg }}
                   flex
                   items-center
                   justify-center">

            <i
                data-lucide="{{ $icon }}"
                class="w-6 h-6 {{ $iconColor }}">
            </i>

        </div>

    </div>

</div>