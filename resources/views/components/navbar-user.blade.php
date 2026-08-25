<div class="relative">

    <button
        id="userDropdownToggle"
        type="button"
        class="
            flex
            items-center
            gap-2
            sm:gap-3
            px-2
            sm:px-3
            py-2
            rounded-2xl
            hover:bg-white/10
            transition
        "
    >

        {{-- Avatar --}}
        <div
            class="
                w-9
                h-9
                sm:w-10
                sm:h-10
                rounded-full
                overflow-hidden
                bg-white
                shrink-0
            "
        >

            <img
                src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name_en) }}&background=ffffff&color=2563eb&size=256"
                alt="{{ auth()->user()->name_en }}"
                class="w-full h-full object-cover"
            >

        </div>


        {{-- User Info --}}
        <div class="text-left hidden sm:block">

            <p class="font-body text-white font-semibold truncate max-w-[180px]">

                {{ auth()->user()->name_kh }}

            </p>

            <p class="text-blue-100 text-sm truncate max-w-[180px]">

                {{ ucfirst(auth()->user()->position ?? 'Administrator') }}

            </p>

        </div>


        {{-- Arrow --}}
        <i
            data-lucide="chevron-down"
            class="w-4 h-4 text-white shrink-0"
        ></i>

    </button>


    {{-- Dropdown --}}
    <div
        id="userDropdownMenu"
        class="
            hidden
            opacity-0
            scale-95
            origin-top-right
            absolute
            right-0
            mt-3
            w-[calc(100vw-2rem)]
            max-w-64
            bg-white
            rounded-2xl
            shadow-xl
            transition-all
            duration-200
            overflow-hidden
            z-50
        "
    >

        {{-- Profile --}}
        <a
            href="{{ route('users.profile') }}"
            class="
                flex
                items-center
                gap-3
                px-5
                py-3
                hover:bg-gray-100
            "
        >

            <i
                data-lucide="user"
                class="w-5 h-5 text-blue-600"
            ></i>

            <span class="font-body text-blue-600">
                ព័ត៌មានផ្ទាល់ខ្លួន
            </span>

        </a>


        {{-- Change Password --}}
        <a
            href="{{ route('users.change-password') }}"
            class="
                flex
                items-center
                gap-3
                px-5
                py-3
                hover:bg-gray-100
            "
        >

            <i
                data-lucide="key-round"
                class="w-5 h-5 text-blue-600"
            ></i>

            <span class="font-body text-blue-600">
                ប្ដូរពាក្យសម្ងាត់
            </span>

        </a>


        <hr>


        {{-- Logout --}}
        <form action="{{ route('logout') }}" method="POST">

            @csrf

            <button
                type="submit"
                class="
                    w-full
                    flex
                    items-center
                    gap-3
                    px-5
                    py-3
                    hover:bg-red-50
                    text-red-600
                    cursor-pointer
                "
            >

                <i
                    data-lucide="log-out"
                    class="w-5 h-5"
                ></i>

                <span class="font-body">
                    ចាកចេញ
                </span>

            </button>

        </form>

    </div>

</div>