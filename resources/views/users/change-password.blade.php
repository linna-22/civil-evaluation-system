@extends('layouts.app')

@section('title', 'ផ្លាស់ប្ដូរពាក្យសម្ងាត់')

@section('content')

<div class="max-w-4xl mx-auto">

    {{-- Page Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">
                ផ្លាស់ប្ដូរពាក្យសម្ងាត់
            </h1>

            <p class="text-gray-500 mt-1">
                ផ្លាស់ប្ដូរពាក្យសម្ងាត់សម្រាប់គណនីរបស់អ្នក
            </p>
        </div>

        <a
            href="{{ route('users.index') }}"
            class="inline-flex items-center px-4 py-2 rounded-lg border border-gray-300 text-gray-700 hover:bg-gray-100 transition"
        >
             <i
                data-lucide="arrow-left"
                class="w-4 h-4"
            ></i>

            ត្រឡប់ក្រោយ
        </a>
    </div>

    {{-- Card --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-200">

        <form
            action="{{ route('users.update-password') }}"
            method="POST"
            class="p-6 space-y-6"
        >
            @csrf
            @method('PUT')

            {{-- User Information --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                

                <x-input
                    label="គោត្តនាមនិងនាម"
                    name="name_kh"
                    :value="$user->name_kh"
                    readonly
                />

                <x-input
                    label="ឈ្មោះឡាតាំង"
                    name="name_en"
                    :value="$user->name_en"
                    readonly
                />

            </div>

            <hr>

            {{-- Password Section --}}
            <div class="space-y-5">

                <x-input
                    label="ពាក្យសម្ងាត់បច្ចុប្បន្ន"
                    name="current_password"
                    type="password"
                    required
                />

                <x-input
                    label="ពាក្យសម្ងាត់ថ្មី"
                    name="password"
                    type="password"
                    required
                />

                <x-input
                    label="បញ្ជាក់ពាក្យសម្ងាត់ថ្មី"
                    name="password_confirmation"
                    type="password"
                    required
                />

            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 pt-6 border-t">

                <button
                    type="submit"
                    class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition"
                >
                    ផ្លាស់ប្ដូរពាក្យសម្ងាត់
                </button>

            </div>

        </form>

    </div>

</div>

@endsection