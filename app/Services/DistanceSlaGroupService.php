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

    private function syncRules(DistanceSlaGroup $group, array $rules): void
    {
        $now = now();
        $rows = [];

        foreach ($rules as $row) {
            if (! is_array($row)) {
                continue;
            }
            $rows[] = [
                'distance_sla_group_id' => $group->id,
                'distance_from'         => $row['distance_from'],
                'distance_to'           => $row['distance_to'],
                'time_with_rider'       => $row['time_with_rider'],
                'time_without_rider'    => $row['time_without_rider'],
                'created_at'            => $now,
                'updated_at'            => $now,
            ];
        }

        if (count($rows) > 0) {
            DistanceSlaRule::insert($rows);
        }
    }
}
