@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="space-y-6">

        {{-- Greeting --}}
        <div class="text-center">

            <h2 class="font-body
           text-gray-500
           mt-4 text-2xl align-middle">

                សូមស្វាគមន៍
                <span>
                    {{ auth()->user()->gender === 'male' ? 'លោក' : 'លោកស្រី' }}
                </span>

                <span class="font-title text-blue-500">
                    {{ auth()->user()->name_kh }}
                </span>

                មកកាន់
                <span class="font-title text-blue-500">
                    ប្រព័ន្ធវាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី
                </span>

            </h2>

        </div>
        @if (auth()->user()->role === 'super_admin')
            {{-- Statistics --}}

            <div
                class="grid
               grid-cols-1
               md:grid-cols-2
               xl:grid-cols-4
               gap-6">

                <x-stat-card title="មន្ត្រីរាជការ" value="{{ number_format($statistics['users']) }}" icon="users"
                    color="blue" />

                <x-stat-card title="អង្គភាព" value="{{ number_format($statistics['organizations']) }}" icon="building-2"
                    color="orange" />

                <x-stat-card title="នាយកដ្ឋាន" value="{{ number_format($statistics['departments']) }}" icon="building"
                    color="green" />

                <x-stat-card title="ការវាយតម្លៃ" value="{{ number_format($statistics['evaluations']) }}"
                    icon="clipboard-check" color="purple" />

            </div>
        @endif

    </div>


@endsection
