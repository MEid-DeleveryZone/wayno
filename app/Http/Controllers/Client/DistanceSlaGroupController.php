<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Http\Traits\ToasterResponser;
use App\Models\DistanceSlaGroup;
use App\Models\DistanceSlaRule;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DistanceSlaGroupController extends BaseController
{
    use ToasterResponser;

    /**
     * Client routes are registered inside Route::domain('{domain}'), so the route always has
     * a `domain` parameter first. ControllerDispatcher passes positional args from array_values()
     * of route parameters — the first method argument would wrongly receive the hostname.
     * Always read the resource id by name from the route.
     */
    private function distanceSlaGroupIdFromRoute(): mixed
    {
        return request()->route('distance_sla_group');
    }

    public function index()
    {
        $groups = DistanceSlaGroup::withCount('rules')
            ->orderBy('name')
            ->paginate(20);

        return view('backend.distance-sla-groups.index', compact('groups'));
    }

    public function create()
    {
        return view('backend.distance-sla-groups.create');
    }

    public function store(Request $request)
    {
        $validator = $this->groupValidator($request, null);
        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $group = new DistanceSlaGroup();
            $group->name = $request->name;
            $group->is_active = $request->boolean('is_active');
            $group->is_default = $request->boolean('is_default');
            $group->save();

            $this->syncRules($group, $request->input('rules', []));
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $toaster = $this->errorToaster('Error', $e->getMessage());

            return redirect()->back()->withInput()->with('toaster', $toaster);
        }

        $toaster = $this->successToaster('Success', __('Distance SLA group created.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function edit()
    {
        $group = DistanceSlaGroup::find($this->distanceSlaGroupIdFromRoute());
        if (! $group) {
            $toaster = $this->errorToaster(__('Not found'), __('No SLA distance group exists with this ID. Run migrations and the DistanceSlaGroup seeder, or open Edit from the list.'));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }
        $group->load('rules');

        return view('backend.distance-sla-groups.edit', ['group' => $group]);
    }

    public function update(Request $request)
    {
        $distance_sla_group = DistanceSlaGroup::find($this->distanceSlaGroupIdFromRoute());

        if (!$distance_sla_group) {
            $toaster = $this->errorToaster(__('Not found'), __('No SLA distance group exists with this ID.'));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }

        $validator = $this->groupValidator($request, $distance_sla_group->id);

        if ($validator->fails()) {
            return redirect()->back()->withInput()->withErrors($validator);
        }

        DB::beginTransaction();
        try {
            $distance_sla_group->name = $request->name;
            $distance_sla_group->is_active = $request->boolean('is_active');
            $distance_sla_group->is_default = $request->boolean('is_default');
            $distance_sla_group->save();

            $distance_sla_group->rules()->delete();
            $this->syncRules($distance_sla_group, $request->input('rules', []));
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $toaster = $this->errorToaster('Error', $e->getMessage());

            return redirect()->back()->withInput()->with('toaster', $toaster);
        }

        $toaster = $this->successToaster('Success', __('Distance SLA group updated.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function destroy()
    {
        $distance_sla_group = DistanceSlaGroup::find($this->distanceSlaGroupIdFromRoute());
        if (! $distance_sla_group) {
            $toaster = $this->errorToaster(__('Not found'), __('No SLA distance group exists with this ID.'));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }
        if ($distance_sla_group->is_default) {
            $toaster = $this->errorToaster('Error', __('Cannot delete the default SLA group. Set another group as default first.'));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }

        $products = Product::where('distance_sla_group_id', $distance_sla_group->id)
            ->select('id', 'title', 'sku')
            ->limit(25)
            ->get();

        if ($products->isNotEmpty()) {
            $names = $products->map(function ($p) {
                return $p->title ?: $p->sku;
            })->implode(', ');
            $toaster = $this->errorToaster('Error', __('This group is assigned to products and cannot be deleted: :names', ['names' => $names]));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }

        $distance_sla_group->delete();
        $toaster = $this->successToaster('Success', __('Distance SLA group deleted.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function search(Request $request)
    {
        $q = trim((string) $request->get('q', ''));
        $query = DistanceSlaGroup::query()->where('is_active', true);

        if ($q !== '') {
            $query->where('name', 'like', '%' . $q . '%');
        }
        $groups = $query->orderBy('name')->limit(50)->get(['id', 'name', 'is_default']);

        $results = $groups->map(function ($g) {
            $label = $g->name;
            if ($g->is_default) {
                $label .= ' (Default)';
            }

            return [
                'id' => $g->id,
                'text' => $label,
            ];
        })->values();

        return response()->json(['results' => $results]);
    }

    public function setDefault()
    {
        $distance_sla_group = DistanceSlaGroup::find($this->distanceSlaGroupIdFromRoute());
        if (!$distance_sla_group) {
            $toaster = $this->errorToaster(__('Not found'), __('No SLA distance group exists with this ID.'));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }

        DB::beginTransaction();

        try {
            $distance_sla_group->is_default = true;
            $distance_sla_group->save();
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            $toaster = $this->errorToaster('Error', $e->getMessage());

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }

        $toaster = $this->successToaster('Success', __('Default SLA group updated.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function show()
    {
        $group = DistanceSlaGroup::find($this->distanceSlaGroupIdFromRoute());

        if (!$group) {
            $toaster = $this->errorToaster(__('Not found'), __('No SLA distance group exists with this ID.'));

            return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
        }

        return redirect()->route('distance-sla-groups.edit', $group);
    }

    private function groupValidator(Request $request, ?int $ignoreId)
    {
        $nameRule = 'required|string|max:255|unique:distance_sla_groups,name';

        if ($ignoreId !== null) {
            $nameRule .= ',' . $ignoreId;
        }

        $validator = Validator::make($request->all(), [
            'name'                          => $nameRule,
            'is_active'                     => 'nullable',
            'is_default'                    => 'nullable',
            'rules'                         => 'required|array|min:1',
            'rules.*.distance_from'         => 'required|numeric|min:0',
            'rules.*.distance_to'           => 'required|numeric',
            'rules.*.time_with_rider'       => 'required|integer|min:1',
            'rules.*.time_without_rider'    => 'required|integer|min:1',
        ]);

        $validator->after(function ($validator) use ($request) {
            $rules = $request->input('rules', []);
            if (!is_array($rules) || count($rules) < 1) {
                return;
            }
            foreach ($rules as $idx => $row) {
                if (!is_array($row)) {
                    continue;
                }
                $from = isset($row['distance_from']) ? (float) $row['distance_from'] : null;
                $to = isset($row['distance_to']) ? (float) $row['distance_to'] : null;

                if ($from !== null && $to !== null && $to <= $from) {
                    $validator->errors()->add('rules.' . $idx . '.distance_to', __('Distance to must be greater than distance from.'));
                }

                $tw = array_key_exists('time_with_rider', $row) ? (int) $row['time_with_rider'] : null;
                $twr = array_key_exists('time_without_rider', $row) ? (int) $row['time_without_rider'] : null;
                if ($tw !== null && $twr !== null && $tw >= 1 && $twr >= 1 && $twr <= $tw) {
                    $validator->errors()->add('rules.' . $idx . '.time_without_rider', __('Time without rider must be greater than time with rider.'));
                }
            }
            $sorted = collect($rules)->sortBy(function ($r) {
                return (float) ($r['distance_from'] ?? 0);
            })->values()->all();
            $n = count($sorted);
            for ($i = 0; $i < $n - 1; $i++) {
                $b1 = (float) ($sorted[$i]['distance_to'] ?? 0);
                $a2 = (float) ($sorted[$i + 1]['distance_from'] ?? 0);
                if ($b1 > $a2) {
                    $validator->errors()->add('rules', __('Distance bands overlap. Sort rows by From (km) ascending and make sure each To (km) does not cross into the next band.'));

                    return;
                }
            }
        });

        return $validator;
    }

    private function syncRules(DistanceSlaGroup $group, array $rules): void
    {
        foreach ($rules as $row) {
            if (!is_array($row)) {
                continue;
            }
            DistanceSlaRule::create([
                'distance_sla_group_id' => $group->id,
                'distance_from'         => $row['distance_from'],
                'distance_to'           => $row['distance_to'],
                'time_with_rider'       => $row['time_with_rider'],
                'time_without_rider'    => $row['time_without_rider'],
            ]);
        }
    }
}
