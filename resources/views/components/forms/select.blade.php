@props([
    'label',
    'name',
    'options' => [],
    'selected' => '',
    'required' => false,
])

<div>

    <label
        for="{{ $name }}"
        class="block
               mb-2
               font-body
               font-medium
               text-gray-700">

        {{ $label }}

        @if($required)

            <span class="text-red-500">*</span>

        @endif

    </label>

    <select

        id="{{ $name }}"

        name="{{ $name }}"

        {{ $attributes->merge([

            'class' => '
                w-full
                h-10
                rounded-xl
                border
                border-gray-300
                bg-white
                px-4
                outline-none
                transition
                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-100'

        ]) }}>

        @foreach($options as $value => $text)

            <option

                value="{{ $value }}"

                @selected(old($name, $selected) == $value)>

                {{ $text }}

            </option>

        @endforeach

    </select>

    @error($name)

        <p class="mt-1 text-sm text-red-500">

            {{ $message }}

        </p>

    @enderror

</div>