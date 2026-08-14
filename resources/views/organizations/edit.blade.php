@extends('layouts.app')

@section('title', 'កែប្រែអង្គភាព')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <x-layout.page-header
        title="អង្គភាព"
        icon="building-2">

        <x-slot:breadcrumb>

            <x-layout.breadcrumb>

                <x-layout.breadcrumb-item
                    title="អង្គភាព"
                    :url="route('organizations.index')" />

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
        title=""
        description=""
        icon="">

        @include('organizations._form', [
            'organization' => $organization,
        ])

    </x-layout.page-card>

</div>

@endsection