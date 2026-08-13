@extends('layouts.app')

@section('title', 'កែប្រែការវាយតម្លៃ')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <x-layout.page-header
        title="ការវាយតម្លៃ"
        icon="settings-2">

        <x-slot:breadcrumb>

            <x-layout.breadcrumb>

                <x-layout.breadcrumb-item
                    title="ការវាយតម្លៃ"
                    :url="route('evaluation-periods.index')" />
                <i
                    data-lucide="chevron-right"
                    class="w-4 h-4">
                </i>

                <x-layout.breadcrumb-item
                    title="កែប្រែការវាយតម្លៃ" />

            </x-layout.breadcrumb>

        </x-slot:breadcrumb>

    </x-layout.page-header>

    <x-layout.page-card
        title=""
        description=""
        icon="">

        @include('evaluation-periods._form', [
            'evaluationPeriod' => $evaluationPeriod,
        ])

    </x-layout.page-card>

</div>

@endsection