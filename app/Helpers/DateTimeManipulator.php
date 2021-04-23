<?php
namespace App\Helpers;

use Carbon\Carbon;

class DateTimeManipulator
{
    public static function dateDifferenceFromNow($date)
    {
        $dateReferance = Carbon::createFromDate(date('Y-m-d H:i:s', strtotime($date)));
        $now = now();
        $difference = $dateReferance->diffInYears($now);

        return $difference;
    }
}