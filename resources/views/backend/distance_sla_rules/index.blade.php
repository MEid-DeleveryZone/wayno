@extends('layouts.vertical', ['demo' => 'creative', 'title' => 'Distance SLA Rules'])
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="page-title">{{ __("Distance SLA Rules") }}</h4>
                <a href="{{ route('distance-sla-rules.create') }}" class="btn btn-info">
                    <i class="mdi mdi-plus-circle mr-1"></i>{{ __("Add") }}
                </a>
            </div>
        </div>
    </div>

    <div class="row mb-2">
        <div class="col-sm-12">
            @if (\Session::has('success'))
                <div class="alert alert-success"><span>{!! \Session::get('success') !!}</span></div>
            @endif
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-centered table-nowrap table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>{{ __("Vendor") }}</th>
                                    <th>{{ __("Scope") }}</th>
                                    <th>{{ __("Distance Range (km)") }}</th>
                                    <th>{{ __("With Rider (min)") }}</th>
                                    <th>{{ __("Without Rider (min)") }}</th>
                                    <th>{{ __("Action") }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rules as $rule)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ optional($rule->vendor)->name ?? 'Global' }}</td>
                                        <td class="text-capitalize">{{ $rule->scope }}</td>
                                        <td>{{ number_format($rule->min_distance, 2) }} - {{ number_format($rule->max_distance, 2) }}</td>
                                        <td>{{ $rule->time_with_rider }}</td>
                                        <td>{{ $rule->time_without_rider }}</td>
                                        <td>
                                            <div class="form-ul" style="width: 70px;">
                                                <div class="inner-div" style="float: left;">
                                                    <a class="action-icon" href="{{ route('distance-sla-rules.edit', $rule->id) }}">
                                                        <i class="mdi mdi-square-edit-outline"></i>
                                                    </a>
                                                </div>
                                                <div class="inner-div">
                                                    <form method="POST" action="{{ route('distance-sla-rules.destroy', $rule->id) }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" onclick="return confirm('Are you sure? You want to delete this rule.')" class="btn btn-primary-outline action-icon">
                                                            <i class="mdi mdi-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="text-center">{{ __("Result not found.") }}</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
