<header class="h-20
           bg-blue-500
           flex
           items-center
           justify-between
           px-6
           shadow-md">

    {{-- Left Side --}}
    <div class="flex items-center gap-4">

        <img src="{{ asset('images/circle_logo.png') }}" alt="Logo" class="w-14 h-14 object-contain">

        <h1 class="font-title
            sidebar-logo-text
                   text-white
                   text-xl
                   leading-relaxed">

            ប្រព័ន្ធវាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី

        </h1>

    </div>

    {{-- Right Side --}}
    <!-- <div class="flex items-center gap-6">

        {{-- Notification --}}
        <button
            class="text-white hover:opacity-80 transition">

            <i data-lucide="bell" class="w-6 h-6"></i>

        </button>

        {{-- User --}}
        <button
            class="flex items-center gap-3 hover:bg-white/10 px-3 py-2 rounded-xl transition">

            <div
                class="w-12 h-12 rounded-full bg-white flex items-center justify-center">

                <i
                    data-lucide="user"
                    class="w-6 h-6 text-primary">
                </i>

            </div>

            <div class="text-left">

                <p class="text-white font-semibold">

                    Administrator

                </p>

                <p class="text-blue-100 text-sm">

                    Super Admin

                </p>

            </div>

            <i
                data-lucide="chevron-down"
                class="w-5 h-5 text-white">
            </i>

        </button>

    </div> -->

    <div class="flex items-center gap-6">

        {{-- Notification --}}
        <button class="text-white hover:opacity-80 transition">

            <i data-lucide="bell" class="w-6 h-6">
            </i>

        </button>

        <x-navbar-user />

    </div>
</header>