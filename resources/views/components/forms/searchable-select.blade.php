@props([
    'label',
    'name',
    'id' => null,
    'options' => [],
    'selected' => '',
    'placeholder' => null,
    'required' => false,
])

<div>

    <label
        for="{{ $id ?? $name }}"
        class="block mb-2 font-body font-medium text-gray-700">

        {{ $label }}

        @if ($required)
            <span class="text-red-500">*</span>
        @endif

    </label>

    <select
        id="{{ $id ?? $name }}"
        name="{{ $name }}"

        {{ $attributes->merge([
            'class' => 'searchable-select w-full',
        ]) }}>

        @if ($placeholder)
            <option value="">
                {{ $placeholder }}
            </option>
        @endif

        @foreach ($options as $value => $text)

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