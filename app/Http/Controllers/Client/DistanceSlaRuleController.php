<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Models\DistanceSlaRule;
use App\Models\Vendor;
use Illuminate\Http\Request;

class DistanceSlaRuleController extends BaseController
{
    public function index()
    {
        $rules = DistanceSlaRule::with('vendor')->orderBy('id', 'desc')->get();
        return view('backend.distance_sla_rules.index')->with(['rules' => $rules]);
    }

    public function create()
    {
        $vendors = Vendor::orderBy('name')->get(['id', 'name']);
        return view('backend.distance_sla_rules.form')->with([
            'rule' => new DistanceSlaRule(),
            'vendors' => $vendors,
            'mode' => 'create',
        ]);
    }

    public function store(Request $request)
    {
        $validated = $this->validateData($request);

        if ($validated['scope'] === 'global') {
            $validated['vendor_id'] = null;
        }

        DistanceSlaRule::create($validated);
        return redirect()->route('distance-sla-rules.index')->with('success', 'Distance SLA rule created successfully!');
    }

    public function edit($domain = '', $id)
    {
        $rule = DistanceSlaRule::findOrFail($id);
        $vendors = Vendor::orderBy('name')->get(['id', 'name']);

        return view('backend.distance_sla_rules.form')->with([
            'rule' => $rule,
            'vendors' => $vendors,
            'mode' => 'edit',
        ]);
    }

    public function update(Request $request, $domain = '', $id)
    {
        $rule = DistanceSlaRule::findOrFail($id);
        $validated = $this->validateData($request);

        if ($validated['scope'] === 'global') {
            $validated['vendor_id'] = null;
        }

        $rule->update($validated);
        return redirect()->route('distance-sla-rules.index')->with('success', 'Distance SLA rule updated successfully!');
    }

    public function destroy($domain = '', $id)
    {
        DistanceSlaRule::where('id', $id)->delete();
        return redirect()->route('distance-sla-rules.index')->with('success', 'Distance SLA rule deleted successfully!');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'scope' => 'required|in:global,vendor',
            'vendor_id' => 'nullable|required_if:scope,vendor|exists:vendors,id',
            'min_distance' => 'required|numeric|min:0',
            'max_distance' => 'required|numeric|gte:min_distance',
            'time_with_rider' => 'required|integer|min:0',
            'time_without_rider' => 'required|integer|min:0',
        ]);
    }
}
