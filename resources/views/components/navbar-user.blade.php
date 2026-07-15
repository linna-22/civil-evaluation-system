<div class="relative">

    <button
        id="userDropdownToggle"
        class="flex items-center gap-3 px-3 py-2 rounded-2xl hover:bg-white/10 transition">

        {{-- Avatar --}}
        <div
            class="w-11 h-11 rounded-full bg-white flex items-center justify-center">

            <i
                data-lucide="user"
                class="w-5 h-5 text-primary">
            </i>

        </div>

        {{-- User Info --}}
        <div class="text-left">

            <p class="font-body text-white font-semibold">

                {{ auth()->user()->name_kh }}

            </p>

            <p class="text-blue-100 text-sm">

                {{ ucfirst(auth()->user()->position ?? 'Administrator') }}

            </p>

        </div>

        <i
            data-lucide="chevron-down"
            class="w-4 h-4 text-white">
        </i>

    </button>

    {{-- Dropdown --}}
    <div
        id="userDropdownMenu"
        class="hidden
               opacity-0
               scale-95
               origin-top-right
               absolute
               right-0
               mt-3
               w-64
               bg-white
               rounded-2xl
               shadow-xl
               transition-all
               duration-200
               overflow-hidden
               z-50">

        {{-- Header --}}
        <!-- <div class="p-5 border-b">

            <p class="font-semibold">

                Administrator

            </p>

            <p class="text-gray-500 text-sm">

                Super Admin

            </p>

        </div> -->

        {{-- Menu --}}
        <a
            href="#"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100 ">

            <i data-lucide="user" class="w-5 h-5 text-blue-600"></i>

            <span class="font-body text-blue-600">

                ព័ត៌មានផ្ទាល់ខ្លួន

            </span>

        </a>

        <a
            href="#"
            class="flex items-center gap-3 px-5 py-3 hover:bg-gray-100">

            <i data-lucide="key-round" class="w-5 h-5 text-blue-600"></i>

            <span class="font-body text-blue-600">

                ប្ដូរពាក្យសម្ងាត់

            </span>

        </a>

        <hr>

        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button
                class="w-full
                       flex
                       items-center
                       gap-3
                       px-5
                       py-3
                       hover:bg-red-50
                       text-red-600
                       cursor-pointer">

                <i
                    data-lucide="log-out"
                    class="w-5 h-5">
                </i>

                <span class="font-body">

                    ចាកចេញ

                </span>

            </button>

        </form>

    </div>

</div>