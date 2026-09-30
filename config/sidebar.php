<?php

return [

    // =====================================================
    // MAIN MENU
    // =====================================================

    [
        'title' => 'មីនុយ',

        'items' => [

            // =================================================
            // Dashboard
            // =================================================
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

            // =================================================
            // Evaluation
            // =================================================
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

            // =================================================
            // Evaluation Results
            // =================================================
            [
                'icon' => 'chart-no-axes-combined',
                'title' => 'ពិនិត្យការវាយតម្លៃ',
                'route' => 'evaluation-results.*',
                'url' => '#',

                'children' => [

                    // Work Performance
                    [
                        'title' => 'សមិទ្ធកម្មការងារ',
                        'route' => 'evaluation-results.work-performance.*',

                        'active_routes' => [
                            'report.work-performance.edit',
                        ],

                        'url' => 'evaluation-results.work-performance.index',
                    ],

                    // Attendance
                    [
                        'title' => 'វត្តមាន',
                        'route' => 'evaluation-results.attendance.*',

                        'active_routes' => [
                            'report.attendance.edit',
                        ],

                        'url' => 'evaluation-results.attendance.index',
                    ],

                    // Behavior
                    [
                        'title' => 'ឥរិយាបថ',
                        'route' => 'evaluation-results.behavior.*',

                        'active_routes' => [
                            'report.behavior-review',
                        ],

                        'url' => 'evaluation-results.behavior.index',
                    ],

                    // Overall
                    [
                        'title' => 'បង្ហាញការវាយតម្លៃរួម',
                        'route' => 'evaluation-results.overall.*',

                        'active_routes' => [
                            'report.review',
                        ],

                        'url' => 'evaluation-results.overall.index',
                    ],

                ],
            ],

            // =================================================
            // Reports
            // =================================================
            [
                'icon' => 'file-text',
                'title' => 'របាយការណ៍',

                // Parent report namespace
                'route' => 'report.*',

                'url' => '#',

                'children' => [

                    [
                        'title' => 'លទ្ធផលការវាយតម្លៃរបស់មន្ត្រី',

                        // Report namespace
                        'route' => 'report.*',

                        'url' => 'report.index',
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