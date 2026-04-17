<?php

namespace Tests\Feature;

use App\Models\ClientPreference;
use App\Models\DistanceSlaRule;
use App\Services\SlaCalculationService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class SlaCalculationServiceFeatureTest extends TestCase
{
    public function test_distance_band_applies_and_updates_eta()
    {
        $service = new FeatureSlaCalculationService();
        $service->preference = new ClientPreference(['use_distance_based_sla' => true]);
        $service->distanceKm = 12.4;
        $service->vendorRule = new DistanceSlaRule(['time_with_rider' => 30, 'time_without_rider' => 45]);

        $eta = Carbon::parse('2026-04-17 08:00:00');
        $result = $service->calculate([
            'vendor_id' => 1,
            'pickup_lat' => 0,
            'pickup_lng' => 0,
            'dropoff_lat' => 0,
            'dropoff_lng' => 0,
            'eta' => $eta,
            'has_rider' => true,
            'client_code' => 'ABC',
        ]);

        $this->assertSame('2026-04-17 08:30:00', $result->format('Y-m-d H:i:s'));
    }

    public function test_toggle_off_keeps_existing_eta_for_regression_safety()
    {
        $service = new FeatureSlaCalculationService();
        $service->preference = new ClientPreference(['use_distance_based_sla' => false]);
        $service->distanceKm = 50;
        $service->vendorRule = new DistanceSlaRule(['time_with_rider' => 90, 'time_without_rider' => 120]);

        $eta = Carbon::parse('2026-04-17 08:00:00');
        $result = $service->calculate([
            'vendor_id' => 1,
            'pickup_lat' => 0,
            'pickup_lng' => 0,
            'dropoff_lat' => 0,
            'dropoff_lng' => 0,
            'eta' => $eta,
            'has_rider' => true,
            'client_code' => 'ABC',
        ]);

        $this->assertSame($eta->format('Y-m-d H:i:s'), $result->format('Y-m-d H:i:s'));
    }
}

class FeatureSlaCalculationService extends SlaCalculationService
{
    public $preference;
    public $distanceKm = 0.0;
    public $vendorRule;
    public $globalRule;

    protected function resolveClientPreference(?string $clientCode): ?ClientPreference
    {
        return $this->preference;
    }

    protected function getDistanceKm(float $pickupLat, float $pickupLng, float $dropoffLat, float $dropoffLng): float
    {
        return $this->distanceKm;
    }

    protected function findVendorRule(int $vendorId, float $distanceKm): ?DistanceSlaRule
    {
        return $this->vendorRule;
    }

    protected function findGlobalRule(float $distanceKm): ?DistanceSlaRule
    {
        return $this->globalRule;
    }
}
