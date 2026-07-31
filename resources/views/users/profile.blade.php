@extends('layouts.app')

@section('title', 'ព័ត៌មានផ្ទាល់ខ្លួន')

@section('content')

    <div class="max-w-6xl mx-auto space-y-6">

        {{-- ================= Profile Header ================= --}}
        <div class="bg-gradient-to-r from-blue-500 to-blue-500 rounded-2xl shadow-lg text-white">

            <div class="py-10 px-6 flex flex-col items-center">

                {{-- Avatar --}}
                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name_en) }}&background=ffffff&color=2563eb&size=200"
                    class="w-36 h-36 rounded-full border-4 border-white shadow-lg">

                <h2 class="mt-5 text-3xl font-bold">
                    {{ $user->name_kh }}
                </h2>

                <p class="text-blue-100 text-lg">
                    {{ $user->position }}
                </p>

                <p class="text-blue-200 mt-1">
                    {{ $user->organization?->name_kh }}
                </p>

                <div class="flex gap-3 mt-5">

                    {{-- Role --}}
                    <span class="px-4 py-1 rounded-full bg-white text-blue-700 font-semibold text-sm">
                        {{ ucfirst($user->role) }}
                    </span>

                    {{-- Status --}}
                    @if ($user->status == 'active')
                        <span class="px-4 py-1 rounded-full bg-green-500 text-white text-sm">
                            សកម្ម
                        </span>
                    @else
                        <span class="px-4 py-1 rounded-full bg-red-500 text-white text-sm">
                            អសកម្ម
                        </span>
                    @endif

                </div>

            </div>

        </div>

        {{-- ================= Cards ================= --}}

        <div class="grid lg:grid-cols-2 gap-6">

            {{-- Basic Information --}}
            <div class="bg-white rounded-2xl shadow">

                <div class="border-b px-6 py-4">
                    <h3 class="font-bold text-lg">
                        ព័ត៌មានមូលដ្ឋាន
                    </h3>
                </div>

                <div class="p-6 space-y-4">

                    <x-info-item label="លេខសម្គាល់" :value="$user->id_code" />

                    <x-info-item label="ឈ្មោះជាភាសាខ្មែរ" :value="$user->name_kh" />

                    <x-info-item label="ឈ្មោះជាអក្សរឡាតាំង" :value="$user->name_en" />

                    <x-info-item label="ឈ្មោះអ្នកប្រើប្រាស់" :value="$user->username" />

                    <x-info-item label="ភេទ" :value="$user->gender == 'male' ? 'ប្រុស' : 'ស្រី'" />

                </div>

            </div>

            {{-- Contact --}}
            <div class="bg-white rounded-2xl shadow">

                <div class="border-b px-6 py-4">
                    <h3 class="font-bold text-lg">
                        ព័ត៌មានទំនាក់ទំនង
                    </h3>
                </div>

                <div class="p-6 space-y-4">

                    <x-info-item label="លេខទូរស័ព្ទ" :value="$user->phone" />

                    <x-info-item label="អ៊ីមែល" :value="$user->email" />

                </div>

            </div>

            {{-- Work --}}
            <div class="bg-white rounded-2xl shadow">

                <div class="border-b px-6 py-4">
                    <h3 class="font-bold text-lg">
                        ព័ត៌មានការងារ
                    </h3>
                </div>

                <div class="p-6 space-y-4">

                    <x-info-item label="អង្គភាព" :value="$user->organization?->org_name_kh" />

                    <x-info-item label="នាយកដ្ឋាន" :value="$user->department?->department_name_kh" />

                    <x-info-item label="មុខតំណែង" :value="$user->position" />

                    {{-- <x-info-item label="តួនាទី" :value="ucfirst($user->role)" /> --}}

                    <x-info-item label="ស្ថានភាព" :value="$user->status == 'active' ? 'សកម្ម' : 'អសកម្ម'" />

                </div>

            </div>

            {{-- Login Information --}}
            <div class="bg-white rounded-2xl shadow">

                <div class="border-b px-6 py-4">
                    <h3 class="font-bold text-lg">
                        ព័ត៌មានចូលប្រើប្រព័ន្ធ
                    </h3>
                </div>

                <div class="p-6 space-y-4">

                    <x-info-item label="ចូលប្រើប្រព័ន្ធចុងក្រោយ" :value="\App\Helpers\DateHelper::khmerDateTime($user->last_login)" />

                    <x-info-item label="IP Address" :value="$user->last_login_ip ?? 'មិនទាន់មាន'" />

                </div>

            </div>

        </div>

    </div>

@endsection
