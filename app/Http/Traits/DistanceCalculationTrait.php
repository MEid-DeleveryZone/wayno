<?php

namespace App\Http\Traits;

trait DistanceCalculationTrait
{
    // Find distance between two lat long points
    public function calulateDistanceLineOfSight($lat1, $lon1, $lat2, $lon2, $unit)
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        }

        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos($dist);
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $unit = strtolower($unit);

        if ($unit == "kilometer") {
            return ($miles * 1.609344);
        } elseif ($unit == "nautical mile") {
            return ($miles * 0.8684);
        }

        return $miles;
    }
}
