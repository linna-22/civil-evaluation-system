@extends('layouts.app')

@section('title', 'កែប្រែនាយកដ្ឋាន')

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
                    title="កែប្រែនាយកដ្ឋាន" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title=""
        description=""
        icon="">

        @include('departments._form', [
            'department' => $department,
            'organizations' => $organizations,
        ])

    </x-layout.page-card>

</div>

@endsection