@extends('layouts.app')

@section('title', 'បង្កើតនាយកដ្ឋាន')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <x-layout.page-header
        title="នាយកដ្ឋាន"
        icon="building-2">

        <x-slot:breadcrumb>

            <x-layout.breadcrumb>

                <x-layout.breadcrumb-item
                    title="នាយកដ្ឋាន"
                    :url="route('departments.index')" />

                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="បង្កើតនាយកដ្ឋាន" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title="ព័ត៌មាននាយកដ្ឋាន"
        description="សូមបំពេញព័ត៌មាននាយកដ្ឋានឱ្យបានត្រឹមត្រូវ"
        icon="clipboard-list">

        @include('departments._form', [
            'department' => null,
        ])

    </x-layout.page-card>

</div>

@endsection