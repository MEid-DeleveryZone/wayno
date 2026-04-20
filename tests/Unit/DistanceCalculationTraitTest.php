<?php

namespace Tests\Unit;

use App\Http\Traits\DistanceCalculationTrait;
use PHPUnit\Framework\TestCase;

class DistanceCalculationTraitTest extends TestCase
{
    public function test_it_returns_zero_for_same_coordinates()
    {
        $calculator = new class {
            use DistanceCalculationTrait;
        };

        $distance = $calculator->calulateDistanceLineOfSight(25.2048, 55.2708, 25.2048, 55.2708, 'kilometer');

        $this->assertSame(0, $distance);
    }

    public function test_it_calculates_known_distance_in_km()
    {
        $calculator = new class {
            use DistanceCalculationTrait;
        };

        $distance = $calculator->calulateDistanceLineOfSight(25.2048, 55.2708, 24.4539, 54.3773, 'kilometer');

        $this->assertGreaterThan(100, $distance);
        $this->assertLessThan(150, $distance);
    }
}
