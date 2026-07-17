@extends('layouts.app')

@section('title', 'បន្ថែមអង្គភាព')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <x-layout.page-header
        title="អង្គភាព"
        icon="building-2">

        <x-slot:breadcrumb>

            <x-layout.breadcrumb>

                <x-layout.breadcrumb-item
                    title="ផ្ទាំងគ្រប់គ្រង"
                    :url="route('dashboard')" />

                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="អង្គភាព"
                    :url="route('organizations.index')" />

                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="បន្ថែមអង្គភាព" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title="ព័ត៌មានអង្គភាព"
        description="សូមបំពេញព័ត៌មានអង្គភាពឱ្យបានត្រឹមត្រូវ"
        icon="clipboard-list">

        @include('organizations._form', [
            'organization' => null,
        ])

    </x-layout.page-card>

</div>

@endsection