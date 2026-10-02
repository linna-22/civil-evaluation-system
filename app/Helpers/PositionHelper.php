<?php

namespace App\Helpers;

class PositionHelper
{
    public static function label(?string $position): string
    {
        return config('positions.options.' . $position, 'មិនមាន');
    }
}