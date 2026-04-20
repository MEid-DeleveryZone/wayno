@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Distance SLA Rule'])
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box">
                <h4 class="page-title">{{ $mode === 'edit' ? __("Edit Distance SLA Rule") : __("Create Distance SLA Rule") }}</h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ $mode === 'edit' ? route('distance-sla-rules.update', $rule->id) : route('distance-sla-rules.store') }}">
                        @csrf
                        @if($mode === 'edit')
                            @method('PUT')
                        @endif

                        <div class="form-group">
                            <label>{{ __("Scope") }}</label>
                            <div>
                                <label class="mr-3">
                                    <input type="radio" name="scope" value="global" {{ old('scope', $rule->scope ?: 'global') === 'global' ? 'checked' : '' }}> {{ __("Global") }}
                                </label>
                                <label>
                                    <input type="radio" name="scope" value="vendor" {{ old('scope', $rule->scope) === 'vendor' ? 'checked' : '' }}> {{ __("Vendor") }}
                                </label>
                            </div>
                            @error('scope')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="form-group">
                            <label for="vendor_id">{{ __("Vendor (nullable for global)") }}</label>
                            <select class="form-control" name="vendor_id" id="vendor_id">
                                <option value="">{{ __("Global") }}</option>
                                @foreach($vendors as $vendor)
                                    <option value="{{ $vendor->id }}" {{ (string)old('vendor_id', $rule->vendor_id) === (string)$vendor->id ? 'selected' : '' }}>{{ $vendor->name }}</option>
                                @endforeach
                            </select>
                            @error('vendor_id')<small class="text-danger">{{ $message }}</small>@enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="min_distance">{{ __("Min Distance (km)") }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="min_distance" name="min_distance" value="{{ old('min_distance', $rule->min_distance) }}">
                                @error('min_distance')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="max_distance">{{ __("Max Distance (km)") }}</label>
                                <input type="number" step="0.01" min="0" class="form-control" id="max_distance" name="max_distance" value="{{ old('max_distance', $rule->max_distance) }}">
                                @error('max_distance')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 form-group">
                                <label for="time_with_rider">{{ __("Time With Rider (minutes)") }}</label>
                                <input type="number" min="0" class="form-control" id="time_with_rider" name="time_with_rider" value="{{ old('time_with_rider', $rule->time_with_rider) }}">
                                @error('time_with_rider')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="col-md-6 form-group">
                                <label for="time_without_rider">{{ __("Time Without Rider (minutes)") }}</label>
                                <input type="number" min="0" class="form-control" id="time_without_rider" name="time_without_rider" value="{{ old('time_without_rider', $rule->time_without_rider) }}">
                                @error('time_without_rider')<small class="text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>

                        <button type="submit" class="btn btn-info">{{ __("Save") }}</button>
                        <a href="{{ route('distance-sla-rules.index') }}" class="btn btn-secondary">{{ __("Cancel") }}</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
