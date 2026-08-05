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
        'class' => 'tom-select w-full'
    ]) }}>

    <option value="">{{ $placeholder }}</option>

    @foreach($options as $value => $text)

        <option value="{{ $value }}" @selected($selected == $value)>
            {{ $text }}
        </option>

    @endforeach

</select>