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

            // Reports
            [
                'icon' => 'chart-no-axes-combined',
                'title' => 'របាយការណ៍',
                'route' => 'reports.*',
                'url' => '#',

                'children' => [

                    [
                        'title' => 'របាយការណ៍ការវាយតម្លៃ',
                        'route' => 'reports.evaluations.*',
                        'url' => '#',
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