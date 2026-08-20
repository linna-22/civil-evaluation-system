@props([
    'title' => null,
    'description' => '',
    'icon' => null,
])

<div {{ $attributes->merge([
    'class' => 'bg-white rounded-2xl shadow-sm',
]) }}>

    {{-- Card Header --}}
    @if ($title || $description || $icon)

        <div class="flex
           items-center
           gap-4
           px-8
           py-6
           border-b">

            @if ($icon)
                <div
                    class="w-12
                   h-12
                   rounded-xl
                   bg-blue-100
                   flex
                   items-center
                   justify-center">

                    <i data-lucide="{{ $icon }}" class="w-6 h-6 text-primary">
                    </i>

                </div>
            @endif

            <div>

                @if ($title)
                    <h2 class="font-body text-lg font-semibold text-gray-800">
                        {{ $title }}
                    </h2>
                @endif

                @if ($description)
                    <p class="text-sm text-gray-500 mt-1">
                        {{ $description }}
                    </p>
                @endif

            </div>

        </div>

    @endif
    {{-- Card Body --}}
    <div class="p-8">

        {{ $slot }}

    </div>

</div>
