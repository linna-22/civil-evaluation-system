<header
    class="
        h-20
        bg-blue-500
        flex
        items-center
        justify-between
        px-4
        sm:px-6
        shadow-md
    "
>

    {{-- =====================================================
        Left Side
    ====================================================== --}}

    <div class="flex items-center gap-3 sm:gap-4 min-w-0">

        {{-- Mobile Menu Button --}}

        <button
            type="button"
            id="mobileMenuBtn"
            class="
                lg:hidden
                w-10
                h-10
                shrink-0
                rounded-xl
                flex
                items-center
                justify-center
                text-white
                hover:bg-white/20
                transition
            "
            aria-label="Open sidebar"
        >

            <i
                data-lucide="menu"
                class="w-6 h-6"
            ></i>

        </button>


        {{-- Logo --}}

        <img
            src="{{ asset('images/circle_logo.png') }}"
            alt="Logo"
            class="
                w-11
                h-11
                sm:w-14
                sm:h-14
                object-contain
                shrink-0
            "
        >


        {{-- Title --}}

        <h1
            class="
                font-title
                sidebar-logo-text
                text-white
                text-sm
                sm:text-lg
                lg:text-xl
                leading-relaxed
                truncate
            "
        >

            ប្រព័ន្ធវាយតម្លៃផ្អែកលើសមិទ្ធកម្មមន្ត្រី

        </h1>

    </div>


    {{-- =====================================================
        Right Side
    ====================================================== --}}

    <div class="flex items-center gap-2 sm:gap-6 shrink-0">

        <x-navbar-user />

    </div>

</header>