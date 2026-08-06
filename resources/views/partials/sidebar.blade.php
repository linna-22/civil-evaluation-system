<aside
    id="sidebar"
    class="sidebar
           w-[290px]
           bg-blue-500
           text-white
           flex
           flex-col
           transition-all
           duration-300">

    {{-- Toggle Button --}}
    <div class="flex justify-end py-2">

        <button
            id="sidebarToggle"
            class="w-12
                   h-12
                   mx-6
                   rounded-2xl
                   flex
                   hover:bg-white/30
                   items-center
                   justify-center
                   transition
                   cursor-pointer">

            <i
                data-lucide="panel-left-close"
                class="w-6 h-6">
            </i>

        </button>
        <!-- class remove from button -->
        <!-- bg-white/10
        hover:bg-white/30 -->

    </div>

    {{-- Menu --}}
    <!-- <div class="flex-1 overflow-hidden">

        <x-sidebar-section title="មីនុយ">

            <x-sidebar-item
                icon="layout-dashboard"
                title="ផ្ទាំងគ្រប់គ្រង"
                route="dashboard"
                :url="route('dashboard')" />
            <x-sidebar-item
                icon="clipboard-check"
                title="ការវាយតម្លៃ" />

            <x-sidebar-item
                icon="bar-chart-3"
                title="របាយការណ៍" />

        </x-sidebar-section>

        <hr class="border-white/20 my-2 mx-5">

        <x-sidebar-section title="ការគ្រប់គ្រង">

            <x-sidebar-item
                icon="building-2"
                title="អង្គភាព" />

            <x-sidebar-item
                icon="building"
                title="នាយកដ្ឋាន" />

            <x-sidebar-item
                icon="users"
                title="អ្នកប្រើប្រាស់" />

        </x-sidebar-section>

        <hr class="border-white/20 my-5 mx-5">

        <x-sidebar-section title="គណនី">

            <x-sidebar-item
                icon="user-circle"
                title="ព័ត៌មានផ្ទាល់ខ្លួន" />

            <x-sidebar-item
                icon="log-out"
                title="ចាកចេញ" />

        </x-sidebar-section>

    </div> -->

    <div class="flex-1 overflow-y-auto">

    {{-- @php
        $user = auth()->user();
        $sidebar = config('sidebar');

        if ($user->role === 'user') {
            $sidebar = collect($sidebar)
                ->map(function ($section) {

                    $section['items'] = collect($section['items'])
                        ->filter(function ($item) {
                            return in_array($item['route'], [
                                'dashboard',
                                'users.profile',
                                'evaluations.index',
                                'evaluations.history',
                                'evaluations.evaluations.create',
                                'logout',
                            ]);
                        })
                        ->values()
                        ->toArray();

                    return $section;
                })
                ->filter(function ($section) {
                    return !empty($section['items']);
                })
                ->values()
                ->toArray();
        }
    @endphp --}}
    @php
        $user = auth()->user();
        $sidebar = config('sidebar');

        switch ($user->role) {
            case 'user':
                $allowedRoutes = [
                    'dashboard',
                    'users.profile',
                    'evaluations.index',
                    'evaluations.history',
                    'evaluations.evaluations.create',
                    'logout',
                ];
                break;
            case 'organization_admin':
                $allowedRoutes = [
                    'dashboard',
                    'evaluations.index',
                    'evaluations.history',
                    'evaluations.list',
                    'users.profile',
                ];
                break;
            default:

                // super_admin & department_admin
                $allowedRoutes = null;

                break;
        }

        if ($allowedRoutes !== null) {

            $sidebar = collect($sidebar)
                ->map(function ($section) use ($allowedRoutes) {

                    $section['items'] = collect($section['items'])
                        ->filter(function ($item) use ($allowedRoutes) {

                            return in_array($item['route'], $allowedRoutes);

                        })
                        ->values()
                        ->toArray();

                    return $section;

                })
                ->filter(function ($section) {

                    return !empty($section['items']);

                })
                ->values()
                ->toArray();
        }
    @endphp

    @foreach($sidebar as $section)

        <x-sidebar-section :title="$section['title']">

            @foreach($section['items'] as $item)

                <x-sidebar-item
                    :icon="$item['icon']"
                    :title="$item['title']"
                    :route="$item['route']"
                    :url="$item['url'] === '#'
                        ? '#'
                        : route($item['url'])" />

            @endforeach

        </x-sidebar-section>

        @if(!$loop->last)

            <hr class="border-white/20 my-3 mx-5">

        @endif

    @endforeach

</div>
</aside>