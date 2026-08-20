@props([
    'label',
    'name',
    'value' => '',
    'rows' => 4,
    'placeholder' => '',
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

    <textarea

        id="{{ $name }}"

        name="{{ $name }}"

        rows="{{ $rows }}"

        placeholder="{{ $placeholder }}"

        {{ $attributes->merge([

            'class' => '
                w-full
                rounded-xl
                border
                border-gray-300
                px-4
                py-3
                resize-none
                outline-none
                transition
                focus:border-blue-500
                focus:ring-4
                focus:ring-blue-100'

        ]) }}

    >{{ old($name, $value) }}</textarea>

    @error($name)

        <p class="mt-1 text-sm text-red-500">

            {{ $message }}

        </p>

    @enderror

</div>