@extends('layouts.app')

@section('title', 'បង្កើតអ្នកប្រើប្រាស់')

@section('content')
{{-- @if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 p-4 rounded mb-4">
        <strong>Validation Errors:</strong>
        <ul class="list-disc ml-5">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}
<div class="max-w-5xl mx-auto space-y-6">

    <x-layout.page-header
        title="អ្នកប្រើប្រាស់"
        icon="user">

        <x-slot:breadcrumb>

            <x-layout.breadcrumb>

                <x-layout.breadcrumb-item
                    title="អ្នកប្រើប្រាស់"
                    :url="route('users.index')" />

                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="បង្កើតអ្នកប្រើប្រាស់" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title=""
        description=""
        icon="">

        @include('users._form', [
            'user' => null,
            'organizations' => $organizations,
        ])

    </x-layout.page-card>

</div>

@endsection