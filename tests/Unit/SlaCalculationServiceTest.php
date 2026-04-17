<?php

namespace Tests\Unit;

use App\Models\ClientPreference;
use App\Models\DistanceSlaRule;
use App\Services\SlaCalculationService;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class SlaCalculationServiceTest extends TestCase
{
    public function test_has_rider_true_uses_time_with_rider()
    {
        $service = new TestableSlaCalculationService();
        $service->preference = $this->makePreference(true);
        $service->distanceKm = 8.2;
        $service->vendorRule = $this->makeRule(40, 60);

        $eta = Carbon::parse('2026-04-17 10:00:00');
        $result = $service->calculate($this->params($eta, true));

        $this->assertSame('2026-04-17 10:40:00', $result->format('Y-m-d H:i:s'));
    }

    public function test_has_rider_false_uses_time_without_rider()
    {
        $service = new TestableSlaCalculationService();
        $service->preference = $this->makePreference(true);
        $service->distanceKm = 8.2;
        $service->vendorRule = $this->makeRule(40, 60);

        $eta = Carbon::parse('2026-04-17 10:00:00');
        $result = $service->calculate($this->params($eta, false));

        $this->assertSame('2026-04-17 11:00:00', $result->format('Y-m-d H:i:s'));
    }

    public function test_vendor_rule_is_preferred_over_global_rule()
    {
        $service = new TestableSlaCalculationService();
        $service->preference = $this->makePreference(true);
        $service->distanceKm = 5;
        $service->vendorRule = $this->makeRule(20, 25);
        $service->globalRule = $this->makeRule(60, 70);

        $eta = Carbon::parse('2026-04-17 10:00:00');
        $result = $service->calculate($this->params($eta, true));

        $this->assertSame('2026-04-17 10:20:00', $result->format('Y-m-d H:i:s'));
    }

    public function test_global_rule_is_used_when_vendor_rule_is_missing()
    {
        $service = new TestableSlaCalculationService();
        $service->preference = $this->makePreference(true);
        $service->distanceKm = 11.5;
        $service->vendorRule = null;
        $service->globalRule = $this->makeRule(35, 45);

        $eta = Carbon::parse('2026-04-17 10:00:00');
        $result = $service->calculate($this->params($eta, true));

        $this->assertSame('2026-04-17 10:35:00', $result->format('Y-m-d H:i:s'));
    }

    public function test_it_falls_back_to_original_eta_when_no_rule_matches()
    {
        $service = new TestableSlaCalculationService();
        $service->preference = $this->makePreference(true);
        $service->distanceKm = 20;
        $service->vendorRule = null;
        $service->globalRule = null;

        $eta = Carbon::parse('2026-04-17 10:00:00');
        $result = $service->calculate($this->params($eta, true));

        $this->assertSame($eta->format('Y-m-d H:i:s'), $result->format('Y-m-d H:i:s'));
    }

    public function test_it_falls_back_to_original_eta_when_toggle_is_off()
    {
        $service = new TestableSlaCalculationService();
        $service->preference = $this->makePreference(false);
        $service->distanceKm = 20;
        $service->vendorRule = $this->makeRule(10, 15);
        $service->globalRule = $this->makeRule(20, 25);

        $eta = Carbon::parse('2026-04-17 10:00:00');
        $result = $service->calculate($this->params($eta, true));

        $this->assertSame($eta->format('Y-m-d H:i:s'), $result->format('Y-m-d H:i:s'));
    }

    private function params(Carbon $eta, bool $hasRider): array
    {
        return [
            'vendor_id' => 10,
            'pickup_lat' => 25.2048,
            'pickup_lng' => 55.2708,
            'dropoff_lat' => 24.4539,
            'dropoff_lng' => 54.3773,
            'eta' => $eta,
            'has_rider' => $hasRider,
            'client_code' => 'TEST',
        ];
    }

    private function makePreference(bool $enabled): ClientPreference
    {
        return new ClientPreference(['use_distance_based_sla' => $enabled]);
    }

    private function makeRule(int $withRider, int $withoutRider): DistanceSlaRule
    {
        return new DistanceSlaRule([
            'time_with_rider' => $withRider,
            'time_without_rider' => $withoutRider,
        ]);
    }
}

class TestableSlaCalculationService extends SlaCalculationService
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
