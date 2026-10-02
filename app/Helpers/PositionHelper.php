<?php

namespace App\Helpers;

class PositionHelper
{
    public static function label(?string $position): string
    {
        return config('position.options.' . $position, 'មិនមាន');
    }
}