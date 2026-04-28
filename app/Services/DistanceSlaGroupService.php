<?php

namespace App\Services;

use App\Models\DistanceSlaGroup;
use App\Models\DistanceSlaRule;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DistanceSlaGroupService
{
    public function create(array $data): DistanceSlaGroup
    {
        return DB::transaction(function () use ($data) {
            $group = new DistanceSlaGroup();
            $group->name = $data['name'];
            $group->is_active = (bool) ($data['is_active'] ?? false);
            $group->is_default = (bool) ($data['is_default'] ?? false);
            $group->save();

            $this->syncRules($group, $data['rules'] ?? []);

            return $group;
        });
    }

    public function update(DistanceSlaGroup $group, array $data): DistanceSlaGroup
    {
        return DB::transaction(function () use ($group, $data) {
            $group->name = $data['name'];
            $group->is_active = (bool) ($data['is_active'] ?? false);
            $group->is_default = (bool) ($data['is_default'] ?? false);
            $group->save();

            $group->rules()->delete();
            $this->syncRules($group, $data['rules'] ?? []);

            return $group->fresh();
        });
    }

    /**
     * @throws \Exception
     */
    public function delete(DistanceSlaGroup $group): void
    {
        if ($group->is_default) {
            throw new \Exception(__('Cannot delete the default SLA group. Set another group as default first.'));
        }

        $products = Product::where('distance_sla_group_id', $group->id)
            ->select('id', 'title', 'sku')
            ->limit(25)
            ->get();

        if ($products->isNotEmpty()) {
            $names = $products->map(function ($p) {
                return $p->title ?: $p->sku;
            })->implode(', ');
            throw new \Exception(__('This group is assigned to products and cannot be deleted: :names', ['names' => $names]));
        }

        DB::transaction(function () use ($group) {
            $group->delete();
        });
    }

    public function setDefault(DistanceSlaGroup $group): void
    {
        DB::transaction(function () use ($group) {
            DistanceSlaGroup::where('id', '!=', $group->id)->update(['is_default' => false]);
            $group->is_default = true;
            $group->save();
        });
    }

    /**
     * Persist rules for a group. The last submitted rule is treated as open-ended
     * and is always stored with `distance_to = NULL` so runtime matching can
     * recognize it without any schema change.
     */
    private function syncRules(DistanceSlaGroup $group, array $rules): void
    {
        $now = now();
        $rows = [];

        $rules = array_values(array_filter($rules, 'is_array'));
        $lastIdx = count($rules) - 1;

        foreach ($rules as $idx => $row) {
            $isLast = ($idx === $lastIdx);
            $distanceTo = $isLast ? null : $this->numericOrNull($row['distance_to'] ?? null);

            $rows[] = [
                'distance_sla_group_id' => $group->id,
                'distance_from'         => $this->numericOrNull($row['distance_from'] ?? null),
                'distance_to'           => $distanceTo,
                'time_with_rider'       => (int) ($row['time_with_rider'] ?? 0),
                'time_without_rider'    => (int) ($row['time_without_rider'] ?? 0),
                'created_at'            => $now,
                'updated_at'            => $now,
            ];
        }

        if (count($rows) > 0) {
            DistanceSlaRule::insert($rows);
        }
    }

    private function numericOrNull($value): ?float
    {
        if ($value === null || $value === '' || (is_string($value) && trim($value) === '')) {
            return null;
        }
        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }
}
