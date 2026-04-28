@php
    $generatorMode = $generatorMode ?? false;
    $ruleRows = [];
    $oldRules = old('rules');
    if ($oldRules !== null && is_array($oldRules)) {
        foreach (array_values($oldRules) as $row) {
            if (is_array($row)) {
                $ruleRows[] = $row;
            }
        }
        if (!$generatorMode && count($ruleRows) < 1) {
            $ruleRows[] = [];
        }
    } elseif (!$generatorMode) {
        if (isset($rules) && $rules !== null && $rules->count()) {
            foreach ($rules as $r) {
                $ruleRows[] = [
                    'distance_from'      => $r->distance_from,
                    'distance_to'        => $r->distance_to,
                    'time_with_rider'    => $r->time_with_rider,
                    'time_without_rider' => $r->time_without_rider,
                ];
            }
        } else {
            $ruleRows[] = [];
        }
    }
    $lastIdx = count($ruleRows) - 1;
@endphp

@if($generatorMode)
<div class="card border mb-3">
    <div class="card-body py-3">
        <p class="mb-2 font-weight-medium text-muted small text-uppercase" style="letter-spacing:.04em">{{ __('Auto-generate distance bands') }}</p>
        {{-- Initial distance is always 1 km; no user input needed. --}}
        <input type="hidden" name="generator_initial" id="gen-initial-hidden" value="0">
        <div class="form-row align-items-end">
            <div class="col-md-6 form-group mb-md-0">
                <label class="small mb-1" for="gen-threshold">{{ __('Final Threshold (km)') }}</label>
                <input type="number" step="1" min="1" id="gen-threshold"
                       class="form-control"
                       value="{{ old('generator_threshold', '') }}"
                       placeholder="{{ __('e.g. 100') }}">
                <input type="hidden" name="generator_threshold" id="gen-threshold-hidden"
                       value="{{ old('generator_threshold', '') }}">
            </div>
            <div class="col-md-6 form-group mb-md-0">
                <label class="small mb-1" for="gen-step">{{ __('Increment Step (km)') }}</label>
                <input type="number" step="1" min="1" id="gen-step"
                       class="form-control"
                       value="{{ old('generator_step', '') }}"
                       placeholder="{{ __('e.g. 20') }}">
                <input type="hidden" name="generator_step" id="gen-step-hidden"
                       value="{{ old('generator_step', '') }}">
            </div>
        </div>
    </div>
</div>
@endif

<div class="table-responsive">
    <table class="table table-bordered" id="rules-table">
        <thead>
            <tr>
                <th>{{ __('Distance from (km)') }}</th>
                <th>{{ __('Distance to (km)') }}</th>
                <th>{{ __('Time with rider (min)') }}</th>
                <th>{{ __('Time without rider (min)') }}</th>
                @if(!$generatorMode)<th></th>@endif
            </tr>
        </thead>
        <tbody id="rules-tbody">
            @if($generatorMode)
                {{-- Rows are built/managed by the generator JS.
                     On a validation failure the server renders the old rows here so JS
                     can harvest the time values before rebuilding. --}}
                @foreach ($ruleRows as $idx => $r)
                    @php
                        $df  = $r['distance_from']      ?? '';
                        $dt  = $r['distance_to']        ?? '';
                        $tw  = $r['time_with_rider']    ?? '';
                        $twr = $r['time_without_rider'] ?? '';
                        $isLast = ($idx === $lastIdx);
                    @endphp
                    <tr class="rule-row{{ $isLast ? ' rule-row-open-ended' : '' }}" data-server-rendered="1">
                        <td>
                            <span class="rule-distance-display">{{ $df }}</span>
                            <input type="hidden" name="rules[{{ $idx }}][distance_from]" value="{{ $df }}">
                        </td>
                        <td>
                            <span class="rule-distance-display {{ $isLast ? 'text-muted' : '' }}">
                                {{ $isLast ? __('No upper limit') : $dt }}
                            </span>
                            @if($isLast)
                                <small class="form-text text-muted">{{ __('Last band — open-ended') }}</small>
                            @endif
                            <input type="hidden" name="rules[{{ $idx }}][distance_to]" value="{{ $isLast ? '' : $dt }}">
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
                    </tr>
                @endforeach
                @if(count($ruleRows) === 0)
                    <tr id="gen-placeholder-row">
                        <td colspan="4" class="text-center text-muted small py-3">
                            {{ __('Set the generator parameters above to create distance bands automatically.') }}
                        </td>
                    </tr>
                @endif
            @else
                {{-- Normal (edit) mode: fully manual rows --}}
                @foreach ($ruleRows as $idx => $r)
                    @php
                        $df  = $r['distance_from']      ?? '';
                        $dt  = $r['distance_to']        ?? '';
                        $tw  = $r['time_with_rider']    ?? '';
                        $twr = $r['time_without_rider'] ?? '';
                        $isLast = ($idx === $lastIdx);
                    @endphp
                    <tr class="rule-row{{ $isLast ? ' rule-row-open-ended' : '' }}">
                        <td>
                            <input type="number" step="0.01" class="form-control @error('rules.'.$idx.'.distance_from') is-invalid @enderror"
                                name="rules[{{ $idx }}][distance_from]" value="{{ $df }}" required min="5">
                            @error('rules.'.$idx.'.distance_from')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </td>
                        <td>
                            <input type="number" step="0.01"
                                class="form-control rule-distance-to @error('rules.'.$idx.'.distance_to') is-invalid @enderror{{ $isLast ? ' bg-light' : '' }}"
                                name="rules[{{ $idx }}][distance_to]"
                                value="{{ $isLast ? '' : $dt }}"
                                placeholder="{{ $isLast ? __('No upper limit') : '' }}"
                                @if($isLast) readonly data-saved-to="{{ $dt }}" @else required @endif>
                            <small class="form-text text-muted rule-open-ended-hint" @if(!$isLast) style="display:none" @endif>
                                {{ __('Last band has no upper limit.') }}
                            </small>
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
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-danger remove-rule">{{ __('Remove') }}</button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>

@if(!$generatorMode)
    <button type="button" class="btn btn-secondary btn-sm" id="add-rule-row">{{ __('Add rule') }}</button>
    <small class="form-text text-muted mt-2 mb-0">
        {{ __('The last band is open-ended and matches every distance greater than or equal to its "Distance from". New bands are always appended after the last one.') }}
    </small>
@else
    <small class="form-text text-muted mt-2 mb-0">
        {{ __('The last band is open-ended (no upper limit). Adjust the generator parameters above to add or remove bands.') }}
    </small>
@endif

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
