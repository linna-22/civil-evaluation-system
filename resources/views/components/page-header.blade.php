@props([
    'title',
    'description' => '',
])

<div class="flex items-center justify-between mb-6">

    <div>

        <h1
            class="font-title
                   text-2xl
                   text-primary">

            {{ $title }}

        </h1>

        @if($description)

            <p
                class="font-body
                       text-gray-500
                       mt-2">

                {{ $description }}

            </p>

        @endif

    </div>

    <div>

        {{ $actions ?? '' }}

    </div>

</div>