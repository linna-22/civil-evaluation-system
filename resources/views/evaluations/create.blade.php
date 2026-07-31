@extends('layouts.app')

@section('title', 'បង្កើតការវាយតម្លៃ')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Breadcrumb --}}
    <x-layout.breadcrumb>
        <x-layout.breadcrumb-item
            title="ការវាយតម្លៃ"
            :url="route('evaluations.index')" />

        <x-layout.breadcrumb-item
            title="/ បង្កើតការវាយតម្លៃ" />
    </x-layout.breadcrumb>
    <div class="mb-3"></div>
    {{-- Progress --}}
    {{-- <x-evaluations.components.progress
        :currentStep="1" /> --}}
        @include('evaluations.components.progress')

    {{-- Wizard --}}
    <x-layout.page-card class="mt-6">

        <div class="wizard-step" data-step="1">

            @include('evaluations.steps.work-performance')

        </div>

        <div
            class="wizard-step hidden"
            data-step="2">

            @include('evaluations.steps.attendance')

        </div>

        <div
            class="wizard-step hidden"
            data-step="3">

            @include('evaluations.steps.behavior')

        </div>

        <div
            class="wizard-step hidden"
            data-step="4">

            @include('evaluations.steps.preview')

        </div>
        <div
            class="wizard-step hidden"
            data-step="5">

            @include('evaluations.steps.evaulation_result')

        </div>

        <div class="mt-8">

            @include('evaluations.components.navigation')

        </div>

    </x-layout.page-card>

</div>

@endsection
@vite('resources/js/evaluation/wizard.js')