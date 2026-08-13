@props([
    'title',
    'icon' => null,
])

<div
    class="bg-white
           rounded-2xl
           shadow-sm
           p-4">

    <div
        class="flex
               items-center
               justify-between">

        <div
            class="flex
                   items-center
                   gap-5">

            @if($icon)

                <div
                    class="w-16
                           h-16
                           rounded-2xl
                           bg-blue-100
                           flex
                           items-center
                           justify-center">

                    <i
                        data-lucide="{{ $icon }}"
                        class="w-6 h-6 text-primary">
                    </i>

                </div>

            @endif

            <div>

                <h1
                    class="font-title
                           text-xl
                           text-primary">

                    {{ $title }}

                </h1>

                <div class="mt-2">

                    {{ $breadcrumb ?? '' }}

                </div>

            </div>

        </div>

        {{ $actions ?? '' }}

    </div>

</div>