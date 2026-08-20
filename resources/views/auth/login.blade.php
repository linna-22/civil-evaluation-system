@extends('layouts.auth')

@section('title', 'Login')

@section('content')

    <div
        class="min-h-screen bg-gradient-to-br from-blue-700 via-blue-600 to-blue-800 flex flex-col items-center justify-center px-4">

        <x-card class="w-full max-w-sm p-8">

            <!-- Logo -->

            <div class="flex justify-center mb-6">

                <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-28 h-28 object-contain">

            </div>

            <!-- Title -->

            <h1 class="font-title text-xl text-blue-600 text-center leading-relaxed">

                ប្រព័ន្ធវាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី

            </h1>

            <form action="{{ route('login.authenticate') }}" method="POST" class="space-y-5 mt-8">

                @csrf

                {{-- Username / Email --}}

                <x-input name="login" label="ឈ្មោះអ្នកប្រើប្រាស់ ឬ អ៊ីមែល"
                    placeholder="បញ្ចូលឈ្មោះអ្នកប្រើប្រាស់ ឬ អ៊ីមែល" />

                {{-- Password --}}

                <div>

                    <x-password name="password" label="ពាក្យសម្ងាត់" placeholder="បញ្ចូលពាក្យសម្ងាត់" />

                </div>

                {{-- Remember Me --}}

                <!-- <label class="flex items-center gap-2 text-sm text-gray-600">

                                <input type="checkbox" name="remember" class="rounded border-gray-300">

                                ចងចាំខ្ញុំ

                            </label> -->

                {{-- Login Button --}}

                <x-button type="submit">

                    ចូលប្រើប្រាស់

                </x-button>

            </form>

        </x-card>

        {{-- Footer --}}

        <div class="text-white text-center mt-8">
            
            <p class="opacity-80 mt-1">
                © {{ date('Y') }}
                ក្រសួងការងារនិងបណ្ដុះបណ្ដាលវិជ្ជាជីវៈ
            </p>
        </div>
    </div>

    <script>
        function togglePassword(id, button) {

            const input = document.getElementById(id);

            const eye = button.querySelector(".icon-eye");

            const eyeOff = button.querySelector(".icon-eye-off");

            if (input.type === "password") {

                input.type = "text";

                eye.classList.add("hidden");

                eyeOff.classList.remove("hidden");

            } else {

                input.type = "password";

                eye.classList.remove("hidden");

                eyeOff.classList.add("hidden");

            }

        }
    </script>

@endsection