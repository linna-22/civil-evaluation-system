@props([
    'name',
    'options' => [],
    'selected' => '',
    'placeholder' => 'ជ្រើសរើស',
])

<select
    name="{{ $name }}"
    id="{{ $name }}"
    {{ $attributes->merge([
        'class' => 'h-11 w-full rounded-xl bg-white px-4 text-sm text-gray-700 focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-100'
    ]) }}>

    <option value="">{{ $placeholder }}</option>

    @foreach($options as $value => $text)
        <option value="{{ $value }}" @selected($selected == $value)>
            {{ $text }}
        </option>
    @endforeach

</select>