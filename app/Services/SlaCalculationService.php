<?php

namespace App\Services;

use App\Http\Traits\DistanceCalculationTrait;
use App\Models\ClientPreference;
use App\Models\DistanceSlaRule;
use Carbon\Carbon;

class SlaCalculationService
{
    use DistanceCalculationTrait;

    public function calculate(array $params): Carbon
    {
        $eta = $params['eta'] instanceof Carbon ? $params['eta'] : Carbon::parse($params['eta']);

        $clientCode = $params['client_code'] ?? null;
        $preference = $this->resolveClientPreference($clientCode);
        if (!$preference || !$preference->use_distance_based_sla) {
            return $eta;
        }

        $distanceKm = $this->getDistanceKm(
            (float) $params['pickup_lat'],
            (float) $params['pickup_lng'],
            (float) $params['dropoff_lat'],
            (float) $params['dropoff_lng']
        );

        $rule = $this->resolveRule((int) $params['vendor_id'], $distanceKm);
        if (!$rule) {
            return $eta;
        }

        $hasRider = (bool)($params['has_rider'] ?? false);
        $minutes = $hasRider ? (int)$rule->time_with_rider : (int)$rule->time_without_rider;

        // TODO (Next Phase): pass result through VendorAvailabilityService and HolidayService
        return $eta->copy()->addMinutes($minutes);
    }

    protected function getDistanceKm(float $pickupLat, float $pickupLng, float $dropoffLat, float $dropoffLng): float
    {
        return round((float)$this->calulateDistanceLineOfSight($pickupLat, $pickupLng, $dropoffLat, $dropoffLng, 'kilometer'), 2);
    }

    protected function resolveClientPreference(?string $clientCode): ?ClientPreference
    {
        if (!empty($clientCode)) {
            return ClientPreference::where('client_code', $clientCode)->first();
        }

        return ClientPreference::first();
    }

    protected function resolveRule(int $vendorId, float $distanceKm): ?DistanceSlaRule
    {
        $vendorRule = $this->findVendorRule($vendorId, $distanceKm);

        if ($vendorRule) {
            return $vendorRule;
        }

        return $this->findGlobalRule($distanceKm);
    }

    protected function findVendorRule(int $vendorId, float $distanceKm): ?DistanceSlaRule
    {
        return DistanceSlaRule::query()
            ->where('scope', 'vendor')
            ->where('vendor_id', $vendorId)
            ->forDistance($distanceKm)
            ->orderBy('min_distance')
            ->first();
    }

    protected function findGlobalRule(float $distanceKm): ?DistanceSlaRule
    {
        return DistanceSlaRule::query()
            ->global()
            ->whereNull('vendor_id')
            ->forDistance($distanceKm)
            ->orderBy('min_distance')
            ->first();
    }
}
