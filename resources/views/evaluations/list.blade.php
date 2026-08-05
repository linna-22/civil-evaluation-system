@extends('layouts.app')

@section('title', 'បញ្ជីការវាយតម្លៃ')

@section('content')

<div class="max-w-7xl mx-auto">

    {{-- Breadcrumb --}}
    <x-layout.breadcrumb>
        <x-layout.breadcrumb-item
            title="បញ្ជីការវាយតម្លៃ" />
    </x-layout.breadcrumb>

    <div class="mb-3"></div>

    {{-- Page Card --}}
    <x-layout.page-card
        title="បញ្ជីការវាយតម្លៃ"
        description="គ្រប់គ្រង និងពិនិត្យមើលការវាយតម្លៃរបស់មន្ត្រីរាជការ">

        {{-- @include('evaluations.components.statistics') --}}

        {{-- <div class="mt-6"></div> --}}

        @include('evaluations.components.filter')

        <div class="mt-6"></div>

        @include('evaluations.components.table')

    </x-layout.page-card>

</div>

@endsection