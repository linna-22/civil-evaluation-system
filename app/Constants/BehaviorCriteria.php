<?php

namespace App\Constants;

class BehaviorCriteria
{
    public const SECTIONS = [

        'ethics' => [

            'title' => '១. ឥរិយាបថ និងវិន័យ',

            'max_score' => 6,

            'items' => [

                'discipline' => [
                    'label' => 'គោរពវិន័យការងារ ម៉ោងពេលធ្វើការ និងបទបញ្ជាផ្ទៃក្នុងរបស់អង្គភាព',
                    'max_score' => 2,
                ],

                'responsibility' => [
                    'label' => 'ស្មារតីទទួលខុសត្រូវ',
                    'max_score' => 2,
                ],

                'professional_ethics' => [
                    'label' => 'ការគោរពឋានានុក្រមការងារ និងគោរពការសម្ងាត់វិជ្ជាជីវៈ និងកាតព្វកិច្ចលក្ខណការណ៍',
                    'max_score' => 2,
                ],

            ],
        ],

        'professional' => [

            'title' => '២. សមត្ថភាពវិជ្ជាជីវៈ',

            'max_score' => 6,

            'items' => [

                'work_performance' => [
                    'label' => 'សមត្ថភាពបំពេញការងារ',
                    'max_score' => 2,
                ],

                'self_development' => [
                    'label' => 'ឆន្ទៈក្នុងការអភិវឌ្ឍសមត្ថភាព ចំណេះដឹង និងជំនាញ',
                    'max_score' => 2,
                ],

                'initiative_creativity' => [
                    'label' => 'មានគំនិតផ្តួចផ្តើម និងច្នៃប្រឌិត (កែលម្អសកម្មភាពការងារដើម្បីប្រសិទ្ធភាព និងជោគជ័យ)',
                    'max_score' => 2,
                ],

            ],
        ],

        'leadership' => [

            'title' => '៣. ភាពជាអ្នកដឹកនាំ',

            'max_score' => 8,

            'items' => [

                'teamwork' => [
                    'label' => 'សហការជាមួយមន្ត្រីរាជការដទៃដើម្បីសម្រេចលទ្ធផលរួម / ស្មារតីជាក្រុម',
                    'max_score' => 2,
                ],

                'interpersonal_skill' => [
                    'label' => 'ទំនាក់ទំនងអន្តរបុគ្គល',
                    'max_score' => 2,
                ],

                'work_under_pressure' => [
                    'label' => 'សមត្ថភាពអនុវត្តការងារក្រោមសម្ពាធ',
                    'max_score' => 2,
                ],

                'leadership' => [
                    'label' => 'សមត្ថភាពភាពជាអ្នកដឹកនាំ',
                    'max_score' => 2,
                ],

            ],
        ],

    ];
}