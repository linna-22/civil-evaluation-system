@extends('layouts.app')
@section('title', 'វាយតម្លៃមន្ត្រី')
@section('content')

    <div class="max-w-7xl mx-auto px-6 py-6">

        <h1 class="text-2xl font-semibold text-gray-800">
            វាយតម្លៃសមិទ្ធកម្មការងារ
        </h1>

        <p class="mt-2 text-gray-600">
            {{ $user->name_kh }}
        </p>

    </div>

@endsection