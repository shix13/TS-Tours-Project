<?php

namespace App\Helpers;
use Carbon\Carbon;

if (!function_exists('isCurrentWeek')) {
    function isCurrentWeek(Carbon $date)
    {
        $now = Carbon::now();
        $startOfWeek = $now->startOfWeek();
        $endOfWeek = $now->endOfWeek();

        // Debugging
        dd($date, $startOfWeek, $endOfWeek);

        return $date->isBetween($startOfWeek, $endOfWeek);
    }
}
