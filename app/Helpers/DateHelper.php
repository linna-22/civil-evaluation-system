<?php

namespace App\Helpers;

use Carbon\Carbon;

class DateHelper
{
    public static function khmerDateTime($datetime): string
    {
        if (!$datetime) {
            return 'មិនទាន់មាន';
        }

        $date = Carbon::parse($datetime);

        $months = [
            1  => 'មករា',
            2  => 'កុម្ភៈ',
            3  => 'មីនា',
            4  => 'មេសា',
            5  => 'ឧសភា',
            6  => 'មិថុនា',
            7  => 'កក្កដា',
            8  => 'សីហា',
            9  => 'កញ្ញា',
            10 => 'តុលា',
            11 => 'វិច្ឆិកា',
            12 => 'ធ្នូ',
        ];

        return sprintf(
            'ថ្ងៃទី%s ខែ%s ឆ្នាំ%s ម៉ោង %s',
            self::toKhmerNumber($date->day),
            $months[$date->month],
            self::toKhmerNumber($date->year),
            self::toKhmerNumber($date->format('H:i'))
        );
    }

    public static function toKhmerNumber($value): string
    {
        $english = ['0','1','2','3','4','5','6','7','8','9'];
        $khmer   = ['០','១','២','៣','៤','៥','៦','៧','៨','៩'];

        return str_replace($english, $khmer, (string) $value);
    }
}