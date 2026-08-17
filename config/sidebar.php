<?php

return [

    [
        'title' => 'មីនុយ',

        'items' => [

            [
                'icon' => 'layout-dashboard',
                'title' => 'ផ្ទាំងគ្រប់គ្រង',
                'route' => 'dashboard',
                'url' => 'dashboard',
            ],
            [
                'icon' => 'clipboard-check',
                'title' => 'ការវាយតម្លៃ',
                'route' => 'evaluations.index',
                'url' => 'evaluations.index',
            ],
            [
                'icon' => 'settings-2',
                'title' => 'កំណត់ការវាយតម្លៃ',
                'route' => 'evaluation-periods.*',
                'url' => 'evaluation-periods.index',
            ],
            // [
            //     'icon' => 'history',
            //     'title' => 'ប្រវត្តិការវាយតម្លៃ',
            //     'route' => 'evaluations.history',
            //     'url' => 'evaluations.history',
            // ],
            [
                'icon' => 'clipboard-list',
                'title' => 'បញ្ជីការវាយតម្លៃ',
                'route' => 'evaluations.list',
                'url' => 'evaluations.list',
            ],

        ],
    ],

    [
        'title' => 'ការគ្រប់គ្រង',

        'items' => [

            [
                'icon' => 'building-2',
                'title' => 'អង្គភាព',
                'route' => 'organizations.*',
                'url' => 'organizations.index',
            ],

            [
                'icon' => 'building',
                'title' => 'នាយកដ្ឋាន',
                'route' => 'departments.*',
                'url' => 'departments.index',
            ],
            [
                'icon' => 'landmark',
                'title' => 'ការិយាល័យ',
                'route' => 'offices.*',
                'url' => 'offices.index',
            ],

            [
                'icon' => 'users',
                'title' => 'អ្នកប្រើប្រាស់',
                'route' => 'users.index',
                'url' => 'users.index',
            ],

        ],
    ],

    // [
    //     'title' => 'គណនី',

    //     'items' => [

    //         [
    //             'icon'  => 'user-circle',
    //             'title' => 'ព័ត៌មានផ្ទាល់ខ្លួន',
    //             'route' => 'users.profile',
    //             'url'   => 'users.profile',
    //         ],

    //         [
    //             'icon'  => 'log-out',
    //             'title' => 'ចាកចេញ',
    //             'route' => 'logout',
    //             'url'   => 'logout',
    //         ],

    //     ],
    // ],

];