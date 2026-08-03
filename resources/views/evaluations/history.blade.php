@extends('layouts.app')

@section('title', 'ផ្ទាំងគ្រប់គ្រង')

@section('content')

    {{-- Breadcrumb --}}
    {{-- <x-layout.breadcrumb>

        <x-layout.breadcrumb-item
            title="ផ្ទាំងគ្រប់គ្រង" />

    </x-layout.breadcrumb> --}}

    {{-- Page Header --}}
    <x-layout.page-header title="ប្រវត្តិការវាយតម្លៃ" description="សូមស្វាគមន៍មកកាន់ប្រព័ន្ធវាយតម្លៃមន្ត្រីរាជការ"
        icon="layout-dashboard" />

    {{-- Evaluation History --}}
    <div class="mt-6">

        <x-dashboard.history-table />

    </div>

@endsection
