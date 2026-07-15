@props([
    'label',
    'name',
    'type' => 'text',
    'placeholder' => '',
])

<div>
    <label
        for="{{ $name }}"
        class="block mb-2 text-gray-700 font-medium">

        {{ $label }}

    </label>

    <input
        id="{{ $name }}"
        name="{{ $name }}"
        type="{{ $type }}"
        value="{{ old($name) }}"
        placeholder="{{ $placeholder }}"

        {{ $attributes->merge([
            'class' => 'w-full h-14 rounded-2xl border border-gray-300 px-5 outline-none focus:ring-2 focus:ring-blue-600 focus:border-blue-600 transition'
        ]) }}>

    @error($name)
        <p class="text-red-500 text-sm mt-2">
            {{ $message }}
        </p>
    @enderror
</div>