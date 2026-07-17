@props([
    'label',
    'name',
    'type' => 'text',
    'value' => '',
    'placeholder' => '',
    'required' => false,
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
        value="{{ $value }}"
        placeholder="{{ $placeholder }}"

        {{ $attributes->merge([
            'class' => 'w-full h-10 rounded-xl border border-gray-300 px-5 outline-none focus:ring-1 focus:ring-blue-300 focus:border-blue-400 transition'
        ]) }}>

    @error($name)
        <p class="text-red-500 text-sm mt-2">
            {{ $message }}
        </p>
    @enderror
</div>