<div class="table-responsive">
    <table class="table table-bordered" id="rules-table">
        <thead>
            <tr>
                <th>{{ __('Distance from (km)') }}</th>
                <th>{{ __('Distance to (km)') }}</th>
                <th>{{ __('Time with rider (min)') }}</th>
                <th>{{ __('Time without rider (min)') }}</th>
                <th></th>
            </tr>
        </thead>
        <tbody id="rules-tbody">
            @php
                $ruleRows = [];
                $oldRules = old('rules');
                if ($oldRules !== null && is_array($oldRules)) {
                    foreach (array_values($oldRules) as $row) {
                        if (is_array($row)) {
                            $ruleRows[] = $row;
                        }
                    }
                    if (count($ruleRows) < 1) {
                        $ruleRows[] = [];
                    }
                } elseif (isset($rules) && $rules !== null && $rules->count()) {
                    foreach ($rules as $r) {
                        $ruleRows[] = [
                            'distance_from' => $r->distance_from,
                            'distance_to' => $r->distance_to,
                            'time_with_rider' => $r->time_with_rider,
                            'time_without_rider' => $r->time_without_rider,
                        ];
                    }
                } else {
                    $ruleRows[] = [];
                }
            @endphp
            @foreach ($ruleRows as $idx => $r)
                @php
                    $df = $r['distance_from'] ?? '';
                    $dt = $r['distance_to'] ?? '';
                    $tw = $r['time_with_rider'] ?? '';
                    $twr = $r['time_without_rider'] ?? '';
                @endphp
                <tr class="rule-row">
                    <td>
                        <input type="number" step="0.01" class="form-control @error('rules.'.$idx.'.distance_from') is-invalid @enderror"
                            name="rules[{{ $idx }}][distance_from]" value="{{ $df }}" required>
                        @error('rules.'.$idx.'.distance_from')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control @error('rules.'.$idx.'.distance_to') is-invalid @enderror"
                            name="rules[{{ $idx }}][distance_to]" value="{{ $dt }}" required>
                        @error('rules.'.$idx.'.distance_to')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="number" class="form-control @error('rules.'.$idx.'.time_with_rider') is-invalid @enderror"
                            name="rules[{{ $idx }}][time_with_rider]" value="{{ $tw }}" required min="1">
                        @error('rules.'.$idx.'.time_with_rider')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </td>
                    <td>
                        <input type="number" class="form-control @error('rules.'.$idx.'.time_without_rider') is-invalid @enderror"
                            name="rules[{{ $idx }}][time_without_rider]" value="{{ $twr }}" required min="1">
                        @error('rules.'.$idx.'.time_without_rider')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </td>
                    <td><button type="button" class="btn btn-sm btn-outline-danger remove-rule">{{ __('Remove') }}</button>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
<button type="button" class="btn btn-secondary btn-sm" id="add-rule-row">{{ __('Add rule') }}</button>
@if($errors->has('rules'))
    <div class="alert alert-danger border-0 shadow-sm mt-3 mb-0" role="alert">
        <div class="d-flex align-items-start">
            <i class="mdi mdi-map-marker-distance mr-2 mt-1"></i>
            <div>
                <strong class="d-block mb-1">{{ __('Distance rules') }}</strong>
                <span class="small mb-0">{{ $errors->first('rules') }}</span>
            </div>
        </div>
    </div>
@endif
