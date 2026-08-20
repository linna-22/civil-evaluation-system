@props([
    'id',
    'title',
])

<div
    id="{{ $id }}"
    class="fixed inset-0 z-50 hidden">

    {{-- Background --}}
    <div
        class="absolute inset-0 bg-black/50"
        data-close-modal>
    </div>

    {{-- Modal --}}
    <div
        class="flex items-center justify-center min-h-screen p-4">

        <div
            class="relative
                   w-full
                   max-w-2xl
                   rounded-3xl
                   bg-white
                   shadow-2xl">

            {{-- Header --}}
            <div
                class="flex
                       items-center
                       justify-between
                       border-b
                       px-6
                       py-4">

                <h2
                    class="font-title
                           text-xl
                           text-blue-500">

                    {{ $title }}

                </h2>

                <button
                    type="button"
                    data-close-modal>

                    <i
                        data-lucide="x"
                        class="w-6 h-6 cursor-pointer">
                    </i>

                </button>

            </div>

            {{-- Body --}}
            <div class="p-6">

                {{ $slot }}

            </div>

        </div>

    </div>

</div>