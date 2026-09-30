<aside id="sidebar"
    class="
        sidebar
        w-[290px]
        lg:w-[290px]
        shrink-0
        bg-blue-500
        text-white
        flex
        flex-col
        transition-all
        duration-300
        fixed
        lg:relative
        inset-y-0
        left-0
        z-50
        -translate-x-full
        lg:translate-x-0
    ">

    {{-- Toggle Button --}}
    <div class="flex justify-end py-2">

        <button id="sidebarToggle" type="button"
            class="
                w-12
                h-12
                mx-4
                lg:mx-6
                rounded-2xl
                flex
                items-center
                justify-center
                hover:bg-white/30
                transition
                cursor-pointer
            ">

            <i data-lucide="panel-left-close" class="w-6 h-6"></i>

        </button>

    </div>


    {{-- Menu --}}
    <div class="flex-1 overflow-y-auto">

        @php
            $user = auth()->user();
            $sidebar = config('sidebar');

            /*
             * ==========================================================
             * Assignment-based access
             * ==========================================================
             *
             * Data-entry assignment is NOT based on role.
             *
             * If the current user has an active assignment:
             *
             * - Office scope     -> Attendance + Work Performance
             * - Department scope -> Attendance + Work Performance
             *
             * The assignment itself controls what users they can evaluate.
             */

            $hasDataEntryAssignment = \App\Models\EvaluationDataEntryAssignment::query()
                ->where('user_id', $user->user_id)
                ->whereHas('evaluationPeriod', function ($query) {
                    $query
                        ->where('status', 'open')
                        ->whereDate('start_date', '<=', now()->toDateString())
                        ->whereDate('end_date', '>=', now()->toDateString());
                })
                ->exists();


            /*
             * ==========================================================
             * Role-based sidebar access
             * ==========================================================
             */

            switch ($user->role) {

                case 'user':

                    $allowedRoutes = [
                        'dashboard',
                        'users.profile',
                        'evaluations.behavior.*',
                        'evaluations.evaluations.create',
                        'logout',
                    ];

                    break;


                case 'evaluation_admin':

                    $allowedRoutes = [
                        'dashboard',
                        'evaluations.*',
                        'users.profile',
                        'evaluation-periods.*',
                    ];

                    break;


                case 'organization_admin':

                    $allowedRoutes = [
                        'dashboard',
                        'evaluations.history',
                        'evaluations.attendance.*',
                        'users.profile',
                        'department-evaluation-results.*',
                        'evaluations.behavior.*',
                    ];

                    break;


                case 'office_admin':

                    $allowedRoutes = [
                        'dashboard',
                        'evaluations.history',
                        'evaluations.work-performance.*',
                        'evaluations.attendance.*',
                        'evaluations.behavior.*',
                        'evaluations.work-attendance.offices',
                        'users.profile',
                    ];

                    break;


                case 'department_admin':

                    $allowedRoutes = [
                        'dashboard',

                        // New evaluation result modules
                        'evaluation-results.*',
                        'evaluation-results.work-performance.*',
                        'evaluation-results.attendance.*',
                        'evaluation-results.behavior.*',
                        'evaluation-results.overall.*',

                        // Report sidebar
                        'my-evaluation-results.*',
                        'department-evaluation-results.*',

                        'users.profile',
                    ];

                    break;


                default:

                    $allowedRoutes = null;

                    break;
            }


            /*
             * ==========================================================
             * Add assignment-based evaluation menus
             * ==========================================================
             *
             * This is the important part.
             *
             * A user who has an active data-entry assignment gets:
             *
             * 1. Work Performance
             * 2. Attendance
             *
             * regardless of whether their role is:
             *
             * user
             * office_admin
             * department_admin
             * etc.
             */

            if ($hasDataEntryAssignment && is_array($allowedRoutes)) {

                $allowedRoutes[] = 'evaluations.work-performance.*';

                $allowedRoutes[] = 'evaluations.attendance.*';

                $allowedRoutes = array_values(
                    array_unique($allowedRoutes)
                );
            }


            /*
             * ==========================================================
             * Filter sidebar
             * ==========================================================
             */

            if ($allowedRoutes !== null) {

                $sidebar = collect($sidebar)
                    ->map(function ($section) use ($allowedRoutes) {

                        $section['items'] = collect($section['items'])

                            ->map(function ($item) use ($allowedRoutes) {

                                /*
                                 * No children
                                 */
                                if (empty($item['children'])) {

                                    return in_array(
                                        $item['route'],
                                        $allowedRoutes
                                    )
                                        ? $item
                                        : null;
                                }


                                /*
                                 * Filter children
                                 */
                                $item['children'] = collect($item['children'])

                                    ->filter(function ($child) use ($allowedRoutes) {

                                        return in_array(
                                            $child['route'],
                                            $allowedRoutes
                                        );
                                    })

                                    ->values()
                                    ->toArray();


                                /*
                                 * Keep parent when it has children
                                 */
                                if (!empty($item['children'])) {

                                    return $item;
                                }


                                /*
                                 * Or keep parent itself
                                 */
                                if (in_array(
                                    $item['route'],
                                    $allowedRoutes
                                )) {

                                    return $item;
                                }


                                return null;
                            })

                            ->filter()

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


        {{-- Render Sidebar --}}
        @foreach ($sidebar as $section)

            <x-sidebar-section :title="$section['title']">

                @foreach ($section['items'] as $item)

                    <x-sidebar-item
                        :icon="$item['icon']"
                        :title="$item['title']"
                        :route="$item['route']"
                        :url="$item['url']"
                        :children="$item['children'] ?? []"
                    />

                @endforeach

            </x-sidebar-section>


            @if (!$loop->last)

                <hr class="border-white/20 my-3 mx-5">

            @endif

        @endforeach

    </div>

</aside>    