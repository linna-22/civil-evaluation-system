<?php

namespace App\Helpers;
use Carbon\CarbonImmutable;

class KhmerHelper
{
    /**
     * Convert month number to Khmer month.
     */
    public static function month($month): string
    {
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

        return $months[(int)$month] ?? '';
    }

    /**
     * Convert Arabic numbers to Khmer numbers.
     */
    public static function number($value): string
    {
        $khmer = [
            '0' => '០',
            '1' => '១',
            '2' => '២',
            '3' => '៣',
            '4' => '៤',
            '5' => '៥',
            '6' => '៦',
            '7' => '៧',
            '8' => '៨',
            '9' => '៩',
        ];

        return strtr((string)$value, $khmer);
    }

    /**
 * Khmer Date
 */
public static function fullDate($date = null): string
{
    $date = $date ?? now();

    return "ថ្ងៃទី "
        . self::number($date->day)
        . " ខែ "
        . self::month($date->month)
        . " ឆ្នាំ "
        . self::number($date->year);
}
public static function lunarDate($date = null): string
{
    $date = $date
        ? CarbonImmutable::parse($date)->setTimezone('Asia/Phnom_Penh')
        : CarbonImmutable::now()->setTimezone('Asia/Phnom_Penh');

    return toLunarDate($date)->toString();
}
}