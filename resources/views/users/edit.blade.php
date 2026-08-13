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
                    title="អង្គភាព"
                    :url="route('users.index')" />

                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="កែប្រែអង្គភាព" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title="ព័ត៌មានអង្គភាព"
        description="សូមកែប្រែព័ត៌មានអង្គភាពឱ្យបានត្រឹមត្រូវ"
        icon="clipboard-list">

        @include('users._form', [
            'user' => $user,
            'organizations' => $organizations,
        ])

    </x-layout.page-card>

</div>

@endsection