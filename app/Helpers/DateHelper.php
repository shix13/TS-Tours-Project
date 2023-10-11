<?php

namespace App\Helpers;

use Carbon\Carbon;

if (!function_exists('isCurrentWeek')) {
    function isCurrentWeek(Carbon $date)
    {
        return $date->isBetween(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek());
    }
}
