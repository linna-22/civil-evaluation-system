@extends('layouts.app')

@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')

    {{-- Breadcrumb --}}
    {{-- <x-layout.breadcrumb>

        <x-layout.breadcrumb-item
            title="ផ្ទាំងគ្រប់គ្រង" />

    </x-layout.breadcrumb> --}}

    {{-- Page Header --}}
    <x-layout.page-header title="ផ្ទាំងគ្រប់គ្រង" description="សូមស្វាគមន៍មកកាន់ប្រព័ន្ធវាយតម្លៃមន្ត្រីរាជការ"
        icon="layout-dashboard" />
    <div class="py-3"></div>

    {{-- Dashboard --}}
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">

        {{-- Employee Information --}}
        <div class="xl:col-span-1">

            <x-dashboard.employee-card :user="$user" />

        </div>

        {{-- Current Evaluation --}}
        <div class="xl:col-span-2">

            <x-dashboard.current-evaluation-card :evaluation="$currentEvaluation" />

        </div>

    </div>


@endsection
