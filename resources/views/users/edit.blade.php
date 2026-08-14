@extends('layouts.app')

@section('title', 'កែប្រែព័ត៌មានអ្នកប្រើប្រាស់')

@section('content')

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
                    title="កែប្រែអ្នកប្រើប្រាស់" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title=""
        description=""
        icon="">

        @include('users._form', [
            'user' => $user,
            'organizations' => $organizations,
        ])

    </x-layout.page-card>

</div>

@endsection