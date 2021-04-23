<?php

namespace App\Services\Filters;

use App\Helpers\DateTimeManipulator;

class Age
{
    private const MIN_AGE = 18;
    private const MAX_AGE = 65;
    
    public static function filter($dateOfBirth)
    {
        $age = DateTimeManipulator::dateDifferenceFromNow($dateOfBirth);

        if (is_null($dateOfBirth) || (($age >= self::MIN_AGE) && ($age <= self::MAX_AGE))) {
            return true;
        }
        return false;
    }
}
