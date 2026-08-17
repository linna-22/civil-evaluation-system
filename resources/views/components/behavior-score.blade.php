@props([
    'name',
    'label',
])

<div class="
    py-5
    border-b
    border-gray-100
    last:border-b-0
">

    <div class="
        flex
        flex-col
        md:flex-row
        md:items-center
        md:justify-between
        gap-4
    ">

        {{-- Criterion --}}

        <div class="flex-1">

            <p class="
                text-sm
                font-medium
                text-gray-700
                leading-6
            ">
                {{ $label }}
            </p>

        </div>


        {{-- Score --}}

        <div class="flex items-center gap-3">

            @foreach([0, 1, 2] as $score)

                <label class="
                    cursor-pointer
                ">

                    <input
                        type="radio"
                        name="{{ $name }}"
                        value="{{ $score }}"
                        class="
                            peer
                            sr-only
                        "
                        required
                    >

                    <span class="
                        inline-flex
                        items-center
                        justify-center
                        min-w-[48px]
                        px-4
                        py-2
                        rounded-lg
                        border
                        border-gray-300
                        text-sm
                        font-medium
                        text-gray-600
                        bg-white
                        transition
                        peer-checked:bg-blue-600
                        peer-checked:text-white
                        peer-checked:border-blue-600
                        hover:bg-gray-50
                        peer-checked:hover:bg-blue-600
                    ">
                        {{ $score }}
                    </span>

                </label>

            @endforeach

        </div>

    </div>

</div>