<?php

return [

    [
        'title' => 'មីនុយ',

        'items' => [

            [
                'icon'  => 'layout-dashboard',
                'title' => 'ផ្ទាំងគ្រប់គ្រង',
                'route' => 'dashboard',
                'url'   => 'dashboard',
            ],

            [
                'icon'  => 'clipboard-check',
                'title' => 'ការវាយតម្លៃ',
                'route' => 'evaluations.*',
                'url'   => '#',
            ],

            [
                'icon'  => 'bar-chart-3',
                'title' => 'របាយការណ៍',
                'route' => 'reports.*',
                'url'   => '#',
            ],

        ],
    ],

    [
        'title' => 'ការគ្រប់គ្រង',

        'items' => [

            [
                'icon'  => 'building-2',
                'title' => 'អង្គភាព',
                'route' => 'organizations.*',
                'url'   => '#',
            ],

            [
                'icon'  => 'building',
                'title' => 'នាយកដ្ឋាន',
                'route' => 'departments.*',
                'url'   => '#',
            ],

            [
                'icon'  => 'users',
                'title' => 'អ្នកប្រើប្រាស់',
                'route' => 'users.*',
                'url'   => '#',
            ],

        ],
    ],

    [
        'title' => 'គណនី',

        'items' => [

            [
                'icon'  => 'user-circle',
                'title' => 'ព័ត៌មានផ្ទាល់ខ្លួន',
                'route' => 'profile',
                'url'   => '#',
            ],

            [
                'icon'  => 'log-out',
                'title' => 'ចាកចេញ',
                'route' => '',
                'url'   => '#',
            ],

        ],
    ],

];