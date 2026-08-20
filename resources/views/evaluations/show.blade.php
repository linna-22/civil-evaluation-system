@extends('layouts.app')

@section('title', 'ព័ត៌មានការវាយតម្លៃ')

@section('content')

<div class="max-w-7xl mx-auto">

    <x-layout.breadcrumb>
        <x-layout.breadcrumb-item
            title="ការវាយតម្លៃ"
            :url="route('evaluations.index')" />

        <x-layout.breadcrumb-item
            title="/ មើលការវាយតម្លៃ" />
    </x-layout.breadcrumb>

    @include('evaluations.show.header')

    @include('evaluations.show.work-performance')

    @include('evaluations.show.attendance')

    @include('evaluations.show.behavior')

    @include('evaluations.show.result')

</div>

@endsection