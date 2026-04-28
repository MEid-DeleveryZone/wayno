<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Client\BaseController;
use App\Http\Requests\Client\DistanceSlaGroupRequest;
use App\Http\Traits\ToasterResponser;
use App\Models\DistanceSlaGroup;
use App\Services\DistanceSlaGroupService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DistanceSlaGroupController extends BaseController
{
    use ToasterResponser;

    /**
     * @var DistanceSlaGroupService
     */
    protected $distanceSlaGroupService;

    public function __construct(DistanceSlaGroupService $distanceSlaGroupService)
    {
        $this->distanceSlaGroupService = $distanceSlaGroupService;
    }

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

    /**
     * @return DistanceSlaGroup|RedirectResponse
     */
    private function resolveGroup()
    {
        $group = DistanceSlaGroup::find($this->distanceSlaGroupIdFromRoute());
        if (! $group) {
            return redirect()->route('distance-sla-groups.index')->with('toaster', $this->errorToaster(__('Not found'), __('No SLA distance group exists with this ID. Run migrations and the DistanceSlaGroup seeder, or open Edit from the list.')));
        }

        return $group;
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

    public function store(DistanceSlaGroupRequest $request)
    {
        $data = array_merge($request->validated(), [
            'is_active'  => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
        ]);

        try {
            $this->distanceSlaGroupService->create($data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('toaster', $this->errorToaster('Error', $e->getMessage()));
        }

        $toaster = $this->successToaster('Success', __('Distance SLA group created.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function edit()
    {
        $group = $this->resolveGroup();
        if ($group instanceof RedirectResponse) {
            return $group;
        }
        $group->load('rules');

        return view('backend.distance-sla-groups.edit', ['group' => $group]);
    }

    public function update(DistanceSlaGroupRequest $request)
    {
        $group = $this->resolveGroup();
        if ($group instanceof RedirectResponse) {
            return $group;
        }

        $data = array_merge($request->validated(), [
            'is_active'  => $request->boolean('is_active'),
            'is_default' => $request->boolean('is_default'),
        ]);

        try {
            $this->distanceSlaGroupService->update($group, $data);
        } catch (\Throwable $e) {
            return redirect()->back()->withInput()->with('toaster', $this->errorToaster('Error', $e->getMessage()));
        }

        $toaster = $this->successToaster('Success', __('Distance SLA group updated.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function destroy()
    {
        $group = $this->resolveGroup();
        if ($group instanceof RedirectResponse) {
            return $group;
        }

        try {
            $this->distanceSlaGroupService->delete($group);
        } catch (\Throwable $e) {
            return redirect()->route('distance-sla-groups.index')->with('toaster', $this->errorToaster('Error', $e->getMessage()));
        }

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

        $results = $groups->map(fn ($g) => [
            'id'   => $g->id,
            'text' => $g->is_default ? $g->name . ' (Default)' : $g->name,
        ])->values();

        return response()->json(['results' => $results]);
    }

    public function setDefault()
    {
        $group = $this->resolveGroup();
        if ($group instanceof RedirectResponse) {
            return $group;
        }

        try {
            $this->distanceSlaGroupService->setDefault($group);
        } catch (\Throwable $e) {
            return redirect()->route('distance-sla-groups.index')->with('toaster', $this->errorToaster('Error', $e->getMessage()));
        }

        $toaster = $this->successToaster('Success', __('Default SLA group updated.'));

        return redirect()->route('distance-sla-groups.index')->with('toaster', $toaster);
    }

    public function show()
    {
        $group = $this->resolveGroup();
        if ($group instanceof RedirectResponse) {
            return $group;
        }

        return redirect()->route('distance-sla-groups.edit', $group);
    }
}
