<?php

return [

    // =====================================================
    // MAIN MENU
    // =====================================================

    [
        'title' => 'មីនុយ',

        'items' => [

            // Dashboard
            [
                'icon' => 'layout-dashboard',
                'title' => 'ផ្ទាំងគ្រប់គ្រង',
                'route' => 'dashboard',
                'url' => '#',

                'children' => [

                    [
                        'title' => 'ផ្ទាំងគ្រប់គ្រង',
                        'route' => 'dashboard',
                        'url' => 'dashboard',
                    ],

                ],
            ],

            // Evaluation
            [
                'icon' => 'clipboard-check',
                'title' => 'ការវាយតម្លៃ',
                'route' => 'evaluations.*',
                'url' => '#',

                'children' => [

                    [
                        'title' => 'កំណត់ការវាយតម្លៃ',
                        'route' => 'evaluation-periods.*',
                        'url' => 'evaluation-periods.index',
                    ],
                    [
                        'title' => 'វាយតម្លៃសមិទ្ធកម្មការងារ',
                        'route' => 'evaluations.work-performance.*',
                        'url' => 'evaluations.work-performance.index',
                    ],

                    [
                        'title' => 'វាយតម្លៃវត្តមាន',
                        'route' => 'evaluations.attendance.*',
                        'url' => 'evaluations.attendance.index',
                    ],

                    [
                        'title' => 'វាយតម្លៃឥរិយាបថ',
                        'route' => 'evaluations.behavior.*',
                        'url' => 'evaluations.behavior.index',
                    ],

                ],
            ],
            [
                'icon' => 'chart-no-axes-combined',
                'title' => 'លទ្ធផលវាយតម្លៃ',
                'route' => 'evaluation-results.*',
                'url' => '#',

                'children' => [

                    [
                        'title' => 'សមិទ្ធកម្មការងារ',
                        'route' => 'evaluation-results.work-performance.*',
                        'active_routes' => [
                            'department-evaluation-results.work-performance.edit',
                        ],
                        'url' => 'evaluation-results.work-performance.index',
                    ],
                    [
                        'title' => 'វត្តមាន',
                        'route' => 'evaluation-results.attendance.*',
                        'active_routes' => [
                            'department-evaluation-results.attendance.edit',
                        ],
                        'url' => 'evaluation-results.attendance.index',
                    ],
                    [
                        'title' => 'ឥរិយាបថ',
                        'route' => 'evaluation-results.behavior.*',
                        'active_routes' => [
                            'department-evaluation-results.behavior-review',
                        ],
                        'url' => 'evaluation-results.behavior.index',
                    ],
                    [
                        'title' => 'លទ្ធផលវាយតម្លៃរួម',
                        'route' => 'evaluation-results.overall.*',
                        'active_routes' => [
                            'department-evaluation-results.review',
                        ],
                        'url' => 'evaluation-results.overall.index',
                    ],

                ],
            ],

            [
                'icon' => 'file-text',
                'title' => 'របាយការណ៍',
                'route' => 'reports.*',
                'url' => '#',

                'children' => [

                    [
                        'title' => 'លទ្ធផលការវាយតម្លៃរបស់មន្ត្រី',
                        'route' => 'department-evaluation-results.*',
                        'url' => 'department-evaluation-results.index',
                    ],

                ],
            ],

        ],
    ],

    // =====================================================
    // MANAGEMENT
    // =====================================================

    [
        'title' => 'ការគ្រប់គ្រង',

        'items' => [

            [
                'icon' => 'building-2',
                'title' => 'ការគ្រប់គ្រង',
                'route' => 'management.*',
                'url' => '#',

                'children' => [

                    [
                        'title' => 'អង្គភាព',
                        'route' => 'organizations.*',
                        'url' => 'organizations.index',
                    ],

                    [
                        'title' => 'នាយកដ្ឋាន',
                        'route' => 'departments.*',
                        'url' => 'departments.index',
                    ],

                    [
                        'title' => 'ការិយាល័យ',
                        'route' => 'offices.*',
                        'url' => 'offices.index',
                    ],

                    [
                        'title' => 'អ្នកប្រើប្រាស់',
                        'route' => 'users.*',
                        'url' => 'users.index',
                    ],

                ],
            ],

        ],
    ],

];